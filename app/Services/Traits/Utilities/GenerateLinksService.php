<?php

namespace App\Services\Traits\Utilities;

use Illuminate\Support\Facades\URL;

trait GenerateLinksService
{
    private function GenerateLinkEngine(string $url_title, array $other_url_params): string
    {
        $generated_url = URL::signedRoute($url_title, $other_url_params);
        return $generated_url;
    }

    protected function GenerateRegisterVerLink(string $url_title, array $other_url_params): string
    {
        $verify_url = $this->GenerateLinkEngine($url_title, $other_url_params);
        return $verify_url;
    }

    protected function GeneratePassResetLink(string $url_title, array $other_url_params): string
    {
        $pass_reset_url = $this->GenerateLinkEngine($url_title, $other_url_params);
        return $pass_reset_url;
    }
}