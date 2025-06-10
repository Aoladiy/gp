<?php

namespace App\Enum;

enum PermissionsEnum: string
{
    case PARTS = 'parts';
    case STORAGE = 'storage';
    case ADDITIONAL = 'additional';
    case EQUIPMENT = 'equipment';
    case AUTHORIZATION = 'authorization';
    case REPORTS = 'reports';
    case SETTINGS = 'settings';
}
