<?php

declare(strict_types=1);

use Illuminate\View\ViewException;
use Rawilk\LaravelBase\Csp\Enums\CspDirective;
use Rawilk\LaravelBase\Csp\Enums\CspKeyword;
use Rawilk\LaravelBase\Csp\Enums\CspScheme;
use Rawilk\LaravelBase\Csp\Enums\CspValue;
use Rawilk\LaravelBase\Csp\Policies\BasicCsp;
use Rawilk\LaravelBase\Csp\Policies\CspPolicy;

const CSP_HEADER = 'Content-Security-Policy';

const CSP_REPORT_HEADER = 'Content-Security-Policy-Report-Only';

it('will output csp headers with the default configuration', function () {
    expect(renderView())
        ->toMatch(metaTagRegex())
        ->toContain("default-src 'self';");
});

it('can set report only csp meta tags', function () {
    $policy = new class extends CspPolicy
    {
        public function configure(): void
        {
            $this->reportOnly();
        }
    };

    expect(renderView($policy::class))
        ->toMatch(metaTagRegex(CSP_REPORT_HEADER));
});

it('wont output any meta tag if not enabled in the config', function () {
    config([
        'csp.enabled' => false,
    ]);

    expect(renderView())->toContain('<head></head>');
});

test('a report uri can be set in the config', function () {
    config(['csp.report_uri' => 'https://report-uri.com']);

    $policy = new class extends CspPolicy
    {
        public function configure(): void
        {
            $this->reportOnly();
        }
    };

    expect(renderView($policy::class))
        ->toContain('report-uri https://report-uri.com');
});

it('will throw an exception when passing no policy class', function () {
    renderView('');
})->throws(ViewException::class, 'The [@cspMetaTag] directive expects to be passed a valid policy class name');

it('will throw an exception for an invalid policy class', function () {
    $invalidPolicyClassName = new class
    {
    };

    renderView($invalidPolicyClassName::class);
})->throws(ViewException::class, 'A valid policy extends ' . CspPolicy::class);

it('will throw an exception when passing none with other values', function () {
    $invalidPolicy = new class extends CspPolicy
    {
        public function configure(): void
        {
            $this->addDirective(CspDirective::Connect, [CspKeyword::None, 'connect']);
        }
    };

    renderView($invalidPolicy::class);
})->throws(ViewException::class, 'The keyword `none` can only be used on its own');

it('can use multiple values for the same directive', function () {
    $policy = new class extends CspPolicy
    {
        public function configure(): void
        {
            $this
                ->addDirective(CspDirective::Frame, 'src-1')
                ->addDirective(CspDirective::Frame, 'src-2')
                ->addDirective(CspDirective::FormAction, 'action-1')
                ->addDirective(CspDirective::FormAction, 'action-2');
        }
    };

    expect(renderView($policy::class))
        ->toHaveMetaContent('frame-src src-1 src-2;form-action action-1 action-2');
});

test('none overrides other values for the same directive', function () {
    $policy = new class extends CspPolicy
    {
        public function configure(): void
        {
            $this
                ->addDirective(CspDirective::Connect, 'connect-1')
                ->addDirective(CspDirective::Frame, 'src-1')
                ->addDirective(CspDirective::Connect, CspKeyword::None);
        }
    };

    expect(renderView($policy::class))
        ->toHaveMetaContent('connect-src \'none\';frame-src src-1');
});

test('values override none value for the same directive', function () {
    $policy = new class extends CspPolicy
    {
        public function configure(): void
        {
            $this
                ->addDirective(CspDirective::Connect, CspKeyword::None)
                ->addDirective(CspDirective::Frame, 'src-1')
                ->addDirective(CspDirective::Connect, CspKeyword::Self);
        }
    };

    expect(renderView($policy::class))
        ->toHaveMetaContent('connect-src \'self\';frame-src src-1');
});

it('can add multiple values for the same directive at one time', function () {
    $policy = new class extends CspPolicy
    {
        public function configure(): void
        {
            $this
                ->addDirective(CspDirective::Frame, ['src-1', 'src-2']);
        }
    };

    expect(renderView($policy::class))
        ->toHaveMetaContent('frame-src src-1 src-2');
});

it('will automatically quote special directive values', function () {
    $policy = new class extends CspPolicy
    {
        public function configure(): void
        {
            $this
                ->addDirective(CspDirective::Script, [CspKeyword::Self]);
        }
    };

    expect(renderView($policy::class))
        ->toHaveMetaContent("script-src 'self'");
});

it('will automatically quote hashed values', function () {
    $policy = new class extends CspPolicy
    {
        public function configure(): void
        {
            $this
                ->addDirective(CspDirective::Script, [
                    'sha256-hash1',
                    'sha384-hash2',
                    'sha512-hash3',
                ]);
        }
    };

    expect(renderView($policy::class))
        ->toHaveMetaContent("script-src 'sha256-hash1' 'sha384-hash2' 'sha512-hash3'");
});

it('will automatically check values when they are given in a single string separated by spaces', function () {
    $policy = new class extends CspPolicy
    {
        public function configure(): void
        {
            $this
                ->addDirective(CspDirective::Script, 'sha256-hash1 ' . CspKeyword::Self->value . ' source');
        }
    };

    expect(renderView($policy::class))
        ->toHaveMetaContent("script-src 'sha256-hash1' 'self' source");
});

it('will not output the same directive values twice', function () {
    $policy = new class extends CspPolicy
    {
        public function configure(): void
        {
            $this
                ->addDirective(CspDirective::Script, [CspKeyword::Self, CspKeyword::Self]);
        }
    };

    expect(renderView($policy::class))
        ->toHaveMetaContent("script-src 'self'");
});

it('will handle scheme values', function () {
    $policy = new class extends CspPolicy
    {
        public function configure(): void
        {
            $this
                ->addDirective(CspDirective::Img, [
                    CspScheme::Data,
                    CspScheme::Https,
                    CspScheme::Ws,
                ]);
        }
    };

    expect(renderView($policy::class))
        ->toHaveMetaContent('img-src data: https: ws:');
});

it('can use an empty value for a directive', function () {
    $policy = new class extends CspPolicy
    {
        public function configure(): void
        {
            $this
                ->addDirective(CspDirective::UpgradeInsecureRequests, CspValue::NO_VALUE)
                ->addDirective(CspDirective::BlockAllMixedContent, CspValue::NO_VALUE);
        }
    };

    expect(renderView($policy::class))
        ->toHaveMetaContent('upgrade-insecure-requests;block-all-mixed-content');
});

// Helpers
function renderView(string $policyName = BasicCsp::class)
{
    return app('view')
        ->file(__DIR__ . '/../fixtures/csp-meta-tags.blade.php')
        ->with('policyName', $policyName)
        ->render();
}

function metaTagRegex(string $headerName = CSP_HEADER, string $content = '.*'): string
{
    return "/<head><meta http-equiv=\"{$headerName}\" content=\"{$content}\"><\/head>/";
}
