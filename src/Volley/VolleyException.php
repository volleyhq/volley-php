<?php

namespace Volley;

/**
 * Exception thrown when a Volley API request fails.
 */
class VolleyException extends \Exception
{
    /**
     * HTTP status code of the failed request.
     *
     * @var int
     */
    private $statusCode;

    /**
     * Initializes a new instance of the VolleyException class.
     *
     * @param string $message Error message
     * @param int $statusCode HTTP status code
     * @param \Throwable|null $previous Previous exception
     */
    public function __construct(string $message = "", int $statusCode = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->statusCode = $statusCode;
    }

    /**
     * Gets the HTTP status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Checks if the exception represents an unauthorized error (401).
     *
     * @return bool
     */
    public function isUnauthorized(): bool
    {
        return $this->statusCode === 401;
    }

    /**
     * Checks if the exception represents a forbidden error (403).
     *
     * @return bool
     */
    public function isForbidden(): bool
    {
        return $this->statusCode === 403;
    }

    /**
     * Checks if the exception represents a not found error (404).
     *
     * @return bool
     */
    public function isNotFound(): bool
    {
        return $this->statusCode === 404;
    }

    /**
     * Checks if the exception represents a rate limit error (429).
     *
     * @return bool
     */
    public function isRateLimited(): bool
    {
        return $this->statusCode === 429;
    }

    /**
     * Checks if the exception represents a server error (5xx).
     *
     * @return bool
     */
    public function isServerError(): bool
    {
        return $this->statusCode >= 500 && $this->statusCode < 600;
    }
}

