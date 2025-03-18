<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Organisateur = 'organisateur';
    case Client = 'client';
}
