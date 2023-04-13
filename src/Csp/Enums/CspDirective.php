<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Csp\Enums;

enum CspDirective: string
{
    case Base = 'base-uri';
    case BlockAllMixedContent = 'block-all-mixed-content';
    case Child = 'child-src';
    case Connect = 'connect-src';
    case Default = 'default-src';
    case Font = 'font-src';
    case FormAction = 'form-action';
    case Frame = 'frame-src';
    case FrameAncestors = 'frame-ancestors';
    case Img = 'img-src';
    case Manifest = 'manifest-src';
    case Media = 'media-src';
    case Object = 'object-src';
    case Plugin = 'plugin-types';
    case Prefetch = 'prefetch-src';
    case Report = 'report-uri';
    case ReportTo = 'report-to';
    case Sandbox = 'sandbox';
    case Script = 'script-src';
    case ScriptAttr = 'script-src-attr';
    case ScriptElem = 'script-src-elem';
    case Style = 'style-src';
    case StyleAttr = 'style-src-attr';
    case StyleElem = 'style-src-elem';
    case UpgradeInsecureRequests = 'upgrade-insecure-requests';
    case WebRtc = 'webrtc-src';
    case Worker = 'worker-src';
}
