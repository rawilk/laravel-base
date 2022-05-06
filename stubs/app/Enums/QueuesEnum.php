<?php

namespace App\Enums;

enum QueuesEnum: string
{
    case DEFAULT_QUEUE = 'default';
    case MAIL = 'mail';
}
