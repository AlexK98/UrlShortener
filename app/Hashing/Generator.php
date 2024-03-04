<?php

namespace App\Hashing;

use App\Config\Consts;
use App\Message\Message;
use App\Storage\LinkStorage;
use App\Validation\UrlValidatorInterface;

class Generator extends Message
{
    private string $linkHash = '';
    private string $seed = '';
    private UrlValidatorInterface $validator;
    private Encoder $encoder;
    private LinkStorage $link;

    public function __construct(UrlValidatorInterface $validator, Encoder $encoder, LinkStorage $link)
    {
        $this->validator = $validator;
        $this->encoder = $encoder;
        $this->link = $link;
    }

    public function getHash(): string
    {
        return $this->linkHash;
    }

    public function setHash(string $hash): void
    {
        $this->linkHash = $hash;
    }

    public function generateUrlHash(string $url): string
    {
        $this->setHash($this->hashURL($url));

        $loopCnt = 0;
        while ($this->link->isHashStored($this->getHash())) {
            $this->genSeed();
            $this->setHash($this->hashURL($url));

            $loopCnt++;
            if ($loopCnt >= Consts::MAX_HASHING_ATTEMPTS) {
                $this->setMessage('Could not find proper Hash after ' . $loopCnt . ' attempts<br>');
                break;
            }
        }

        return $this->getHash();
    }
    
    private function hashURL(string $url): string
    {
        // Handle non-ASCII characters
        $url = idn_to_ascii($url);

        if (!$this->validator->isValid($url)) {
            $this->setMessage($this->validator->getMessage());
            return '';
        }

        $hash = sha1($url . $this->getSeed());
        $shrunkHash = substr($hash, 0, Consts::HASH_STR_LENGTH);

        return $this->encoder->encodeHash($shrunkHash);
    }

    private function getSeed(): string
    {
        return $this->seed;
    }

    private function genSeed(): void
    {
        $this->seed = uniqid();
    }
}
