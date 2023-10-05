<?php

namespace NineDigit\NWS4;

class HttpClientOptions
{
    /**
     * Url adresa servera
     * @example "https://example.com"
     */
    public string $url;
    /**
     * Verejný kľúč pre prístup k API
     */
    public string $publicKey;
    /**
     * Súkromný kľúč pre prístup k API
     */
    public string $privateKey;

    /**
     * Adresa Proxy servera
     * @example "127.0.0.1:8888"
     */
    public ?string $proxyUrl;

    /**
     * Predvolené hlavičky požiadavky
     */
    public array $defaultHeaders;

    /**
     * Iba na účely testovania
     */
    public ?HttpRequestSignerInterface $httpRequestSigner = null;

    /* Indikuje aktívnosť vývojárského režímu, v ktorom
     * je napr. možné vidieť záznamy vykonaných HTTP požiadaviek.
     *
     * Viďte https://docs.guzzlephp.org/en/stable/request-options.html#debug
     */
    public bool $debug = false;

    public function __construct(
        string  $publicKey,
        string  $privateKey,
        string  $url,
        ?array  $defaultHeaders = null,
        ?string $proxyUrl = null,
        ?bool $debug = false
    )
    {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
        $this->url = $url;
        $this->proxyUrl = $proxyUrl;
        $this->defaultHeaders = $defaultHeaders ?? [];
        $this->debug = $debug;
    }

    public static function load($filenameOrData): HttpClientOptions
    {
        $data = [];

        if (is_string($filenameOrData)) {
            $contents = file_get_contents($filenameOrData);
            $data = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        } else if (is_array($filenameOrData)) {
            $data = $filenameOrData;
        } else {
            throw new \InvalidArgumentException("Expecting string or array as an argument.");
        }

        $publicKey = $data['publicKey'];
        $privateKey = $data['privateKey'];
        $url = $data['url'];

        $debug = false;
        $defaultHeaders = null;
        $proxyUrl = null;

        if (array_key_exists('defaultHeaders', $data)) {
            $proxyUrl = $data['defaultHeaders'];
        }

        if (array_key_exists('proxyUrl', $data)) {
            $proxyUrl = $data['proxyUrl'];
        }

        if (array_key_exists('debug', $data)) {
            $debug = $data['debug'];
        }

        return new HttpClientOptions($publicKey, $privateKey, $url, $defaultHeaders, $proxyUrl, $debug);
    }

    // public function save(string $filename): void {
    //   $data = array(
    //     'publicKey' => $this->publicKey,
    //     'privateKey' => $this->privateKey,
    //     'url' => $this->url
    //   );

    //   if ($this->proxyUrl !== null) {
    //     $data['proxyUrl'] = $this->proxyUrl;
    //   }

    //   if ($this->defaultHeaders !== null) {
    //     $data['defaultHeaders'] = $this->defaultHeaders;
    //   }

    //   $json = json_encode($data, JSON_THROW_ON_ERROR);
    //   file_put_contents($filename, $json);
    // }
}
