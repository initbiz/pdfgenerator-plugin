<?php

namespace Initbiz\PDFGenerator\Classes;


use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ChromePdfGenerator
{
    protected  string $executable;
    protected  array $options = ['--no-sandbox',
        '--headless=new',
        '--disable-gpu',
        '--run-all-compositor-stages-before-draw',
        '--virtual-time-budget=5000'];
    protected  string $baseTemp;

    public function __construct($storagePath = "app/tmp", $executable = "google-chrome-stable"){
        // Prepare base temp directory
        $this->executable = $executable;
        $this->prepareBaseTemp($storagePath);
        $this->prepareCatalog();
    }

    protected function prepareBaseTemp($storagePath){
        $this->baseTemp = storage_path($storagePath);
        if (!is_dir($this->baseTemp)) {
            mkdir($this->baseTemp, 0755, true);
        }
    }

    protected  function prepareCatalog(){
        // Create isolated user-data-dir for Chrome
        $userDataDir = $this->baseTemp . DIRECTORY_SEPARATOR . 'chrome_user_data';
        if (!is_dir($userDataDir)) {
            mkdir($userDataDir, 0700, true);
        }
        $this->options[] = '--user-data-dir=' . escapeshellarg($userDataDir);
    }

    protected function buildCliParams(array $options): array {
        $params = [];

        foreach ($options as $key => $value) {
            if (is_bool($value)) {
                if ($value) {
                    $params[] = "--$key";
                }
            } else {
                $params[] = "--$key=" . escapeshellarg($value);
            }
        }

        return $params;
    }

    /**
     * Generate a PDF from raw HTML using headless Chrome,
     * ensuring a writable user-data directory and full completion.
     *
     * @param string $html The raw HTML content to convert.
     * @param string $filename Desired PDF filename (with or without .pdf extension).
     * @param array $options Optional Chrome CLI flags (without URL or --print-to-pdf).
     * @return string            Full path to the generated PDF file in the temp folder.
     * @throws \RuntimeException If PDF generation fails or output is missing.
     */
    public function generate(string $html, string $filename, array $options = []): string
    {
        // Create temporary HTML file
        $htmlPath = $this->baseTemp . DIRECTORY_SEPARATOR . 'chrome_html_' . uniqid() . '.html';
        file_put_contents($htmlPath, $html);

        // Normalize PDF filename and ensure storage dir exists
        $filename = pathinfo($filename, PATHINFO_EXTENSION) === 'pdf' ? $filename : $filename . '.pdf';
        $outputPath = $filename;

        // Default Chrome flags

        $this->options[] = "--print-to-pdf=" . escapeshellarg($outputPath);

        // Build full command and merge stderr/stdout
        $cmd = array_merge([$this->executable], $this->options, $this->buildCliParams($options), [escapeshellarg($htmlPath)]);
        $cmdString = implode(' ', $cmd) . ' 2>&1';
        $process = Process::fromShellCommandline($cmdString);
        $process->setTimeout(null);

        // Ensure Chrome runs under a writable HOME
        $process->setEnv(['HOME' => $this->baseTemp]);

        try {
            $process->mustRun();
        } catch (ProcessFailedException $e) {
            @unlink($htmlPath);
            $output = trim($process->getErrorOutput() ?: $process->getOutput());
            throw new \RuntimeException('PDF generation failed: ' . $e->getMessage() . '\nChrome output: ' . $output);
        }

        @unlink($htmlPath);

        // Wait for any remaining Chrome child processes
        if (!$process->isTerminated()) {
            $process->wait();
        }

        // Verify output file
        if (!file_exists($outputPath)) {
            $msg = trim($process->getOutput() . ' ' . $process->getErrorOutput());
            throw new \RuntimeException("PDF not found at {$outputPath}. Output: {$msg}");
        }

        return $outputPath;
    }
}
