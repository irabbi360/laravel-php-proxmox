<?php

namespace Irabbi360\Proxmox;

use Exception;
use Irabbi360\Proxmox\Traits\Authenticator;

class Proxmox
{
    use Authenticator;

    private $ticket;
    private $csrf;

    /**
     * Authenticate with Proxmox API
     *
     * @return bool
     * @throws Exception
     */
    public function login(): bool
    {
        $response = $this->authenticate();

        if (isset($response['data']['ticket']) && isset($response['data']['CSRFPreventionToken'])) {
            $this->ticket = $response['data']['ticket'];
            $this->csrf = $response['data']['CSRFPreventionToken'];
            return true;
        }

        throw new Exception('Authentication failed');
    }

    /**
     * Get the Ticket
     *
     * @return string
     */
    public function getTicket(): string
    {
        return $this->ticket;
    }

    /**
     * Get the CSRF
     *
     * @return string
     */
    public function getCsrf(): string
    {
        return $this->csrf;
    }    
}
