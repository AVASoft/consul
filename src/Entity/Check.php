<?php


namespace Avasoft\Component\Consul\Entity;


class Check
{
    /**
     * Specifies the name of the check.
     * @example "AMS-1"
     *
     * @var string
     * @see https://www.consul.io/api/agent/check#name
     */
    private string $name;

    /**
     * Specifies arbitrary information for humans. This is not used by Consul internally.
     * @example "Customer note"
     *
     * @var string
     */
    private string $notes;

    /**
     * Specifies that checks associated with a service should deregister after this time.
     * @example "10m"
     *
     * @var string
     * @see https://www.consul.io/api/agent/check#deregistercriticalserviceafter
     */
    private string $deregisterCriticalServiceAfter;

    /**
     * Specifies an HTTP check to perform a GET request against the value of HTTP (expected to be a URL) every Interval.
     * @example https://example.com/check-path
     *
     * @var string
     * @see https://www.consul.io/api/agent/check#http
     */
    private string $http;

    /**
     * Specifies a different HTTP method to be used for an HTTP check. When no value is specified, GET is used.
     * @example "POST"
     *
     * @var string
     */
    private string $method;

    /**
     * Specifies a set of headers that should be set for HTTP checks. Each header can have multiple values.
     * @example map[string][]string: {}
     *
     * @var array
     *
     * @see https://www.consul.io/api/agent/check#header
     */
    private array $header;

    /**
     * Specifies a body that should be sent with HTTP checks
     * @example "{\"check\":\"mem\"}"
     *
     * @var string
     * @see https://www.consul.io/api/agent/check#body
     */
    private string $body;

    /**
     * Specifies if the certificate for an HTTPS check should not be verified.
     * @example true
     *
     * @var bool
     * @see https://www.consul.io/api/agent/check#tlsskipverify
     */
    private bool $TLSSkipVerify;


    /**
     * Specifies the frequency at which to run this check. This is required for HTTP and TCP checks.
     * @example "1m"
     *
     * @var string
     * @see https://www.consul.io/api/agent/check#interval
     */
    private string $interval;

    /**
     * Specifies a timeout for outgoing connections in the case of a Script, HTTP, TCP, or gRPC check.
     * Can be specified in the form of "10s" or "5m" (i.e., 10 seconds or 5 minutes, respectively).
     * @example "10s"
     *
     * @var string
     * @see https://www.consul.io/api/agent/check#timeout
     */
    private string $timeout;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getNotes(): string
    {
        return $this->notes;
    }

    /**
     * @param string $notes
     */
    public function setNotes(string $notes): void
    {
        $this->notes = $notes;
    }

    /**
     * @return string
     */
    public function getDeregisterCriticalServiceAfter(): string
    {
        return $this->deregisterCriticalServiceAfter;
    }

    /**
     * @param string $deregisterCriticalServiceAfter
     */
    public function setDeregisterCriticalServiceAfter(string $deregisterCriticalServiceAfter): void
    {
        $this->deregisterCriticalServiceAfter = $deregisterCriticalServiceAfter;
    }

    /**
     * @return string
     */
    public function getHttp(): string
    {
        return $this->http;
    }

    /**
     * @param string $http
     */
    public function setHttp(string $http): void
    {
        $this->http = $http;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    /**
     * @return array
     */
    public function getHeader(): array
    {
        return $this->header;
    }

    /**
     * @param array $header
     */
    public function setHeader(array $header): void
    {
        $this->header = $header;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return bool
     */
    public function isTLSSkipVerify(): bool
    {
        return $this->TLSSkipVerify;
    }

    /**
     * @param bool $TLSSkipVerify
     */
    public function setTLSSkipVerify(bool $TLSSkipVerify): void
    {
        $this->TLSSkipVerify = $TLSSkipVerify;
    }

    /**
     * @return string
     */
    public function getInterval(): string
    {
        return $this->interval;
    }

    /**
     * @param string $interval
     */
    public function setInterval(string $interval): void
    {
        $this->interval = $interval;
    }

    /**
     * @return string
     */
    public function getTimeout(): string
    {
        return $this->timeout;
    }

    /**
     * @param string $timeout
     */
    public function setTimeout(string $timeout): void
    {
        $this->timeout = $timeout;
    }

    /**
     * @return array
     */
    public function getCheck():array
    {
        return get_object_vars($this);
    }
}