<?php


namespace Dymantic\Secretary;


interface SecretaryMessage
{
    public function sender();

    public function senderEmail();

    public function body();

    public function messageNotes();

    public function toDataArray();
}