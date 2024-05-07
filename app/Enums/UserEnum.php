<?php

namespace App\Enums;

enum UserEnum: string
{
    case USER = 'user';
    case ADMIN = 'admin';
    case MODERATOR = 'moderator';
}
