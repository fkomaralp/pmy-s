<?php

namespace App\Models;


class EmailData
{
    private $public_url;

    /**
     * @return mixed
     */
    public function getPublicUrl()
    {
        return $this->public_url;
    }

    /**
     * @param mixed $public_url
     * @return EmailData
     */
    public function setPublicUrl($public_url)
    {
        $this->public_url = $public_url;
        return $this;
    }
}
