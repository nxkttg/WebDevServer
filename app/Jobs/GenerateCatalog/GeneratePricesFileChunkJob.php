<?php

namespace App\Jobs\GenerateCatalog;

class GeneratePricesFileChunkJob extends AbstractJob
{
    private $chunk;
    private int $fileNum;

    public function __construct($chunk, int $fileNum)
    {
        parent::__construct();

        $this->chunk = $chunk;
        $this->fileNum = $fileNum;
    }

    public function handle()
    {
        $this->debug("done chunk {$this->fileNum}");
    }
}
