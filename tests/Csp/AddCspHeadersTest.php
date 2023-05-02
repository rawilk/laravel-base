<?php

declare(strict_types=1);

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Route;
use Rawilk\LaravelBase\Csp\Enums\CspDirective;
use Rawilk\LaravelBase\Csp\Enums\CspKeyword;
use Rawilk\LaravelBase\Csp\Enums\CspScheme;
use Rawilk\LaravelBase\Csp\Enums\CspValue;
use Rawilk\LaravelBase\Csp\Middleware\AddCspHeaders;
use Rawilk\LaravelBase\Csp\Policies\CspPolicy;
use Rawilk\LaravelBase\Exceptions\InvalidCspDirective;
use Rawilk\LaravelBase\Exceptions\InvalidCspPolicy;
use Rawilk\LaravelBase\Exceptions\InvalidCspValueSet;
use Symfony\Component\HttpFoundation\HeaderBag;

const CSP_HEADER = 'Content-Security-Policy';

const CSP_REPORT_HEADER = 'Content-Security-Policy-Report-Only';

beforeEach(function () {
    app(Kernel::class)->pushMiddleware(AddCspHeaders::class);

    Route::get('test-route', function (): string {
        return 'ok';
    });
});

// Helpers
function getResponseHeaders(string $url = 'test-route'): HeaderBag
{
    return test()
        ->get($url)
        ->assertSuccessful()
        ->headers;
}

it('wil set csp headers with default configuration', function () {
    $headers = getResponseHeaders();

    expect($headers->get(CSP_HEADER))->toContain("default-src 'self';")
        ->and($headers->get(CSP_REPORT_HEADER))->toBeNull();
});

it('can set report only csp headers', function () {
    config([
        'csp.report_only' => true,
    ]);

    $headers = getResponseHeaders();

    expect($headers->get(CSP_REPORT_HEADER))->toContain("default-src 'self';")
        ->and($headers->get(CSP_HEADER))->toBeNull();
});

it('will not set any headers if not enabled in the config', function () {
    config([
        'csp.enabled' => false,
    ]);

    $headers = getResponseHeaders();

    expect($headers->get(CSP_HEADER))->toBeNull()
        ->and($headers->get(CSP_REPORT_HEADER))->toBeNull();
});

test('a report uri can be set in the config', function () {
    config([
        'csp.report_uri' => 'https://report-uri.com',
    ]);

    $headers = getResponseHeaders();

    $cspHeader = $headers->get(CSP_HEADER);

    expect($cspHeader)->toContain('report-uri https://report-uri.com');
});

it('will throw an exception when using an invalid policy class', function () {
    withoutExceptionHandling();

    $invalidPolicyClassName = new class
    {
    };

    config(['csp.policy' => $invalidPolicyClassName::class]);

    getResponseHeaders();
})->throws(InvalidCspPolicy::class);

it('will throw an exception when passing none with other values', function () {
    withoutExceptionHandling();

    $invalidPolicy = new class extends CspPolicy
    {
        public function configure(): void
        {
            $this->addDirective(CspDirective::Connect, [CspKeyword::None, 'connect']);
        }
    };

    config(['csp.policy' => $invalidPolicy::class]);

    getResponseHeaders();
})->throws(InvalidCspValueSet::class);

it('will throw an exception when using an invalid csp directive', function () {
    withoutExceptionHandling();

    $invalidPolicy = new class extends CspPolicy
    {
        public function configure(): void
        {
            $this->addDirective('invalid-directive', 'value');
        }
    };

    config(['csp.policy' => $invalidPolicy::class]);

    getResponseHeaders();
})->throws(InvalidCspDirective::class);

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

    config(['csp.policy' => $policy::class]);

    $headers = getResponseHeaders();

    expect($headers->get(CSP_HEADER))->toEqual('frame-src src-1 src-2;form-action action-1 action-2');
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

    config(['csp.policy' => $policy::class]);

    $headers = getResponseHeaders();

    expect($headers->get(CSP_HEADER))->toEqual('connect-src \'none\';frame-src src-1');
});

test('values override none value for same directive', function () {
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

    config(['csp.policy' => $policy::class]);

    $headers = getResponseHeaders();

    expect($headers->get(CSP_HEADER))->toEqual('connect-src \'self\';frame-src src-1');
});

test('a policy can configure itself to be put in report only mode', function () {
    $policy = new class extends CspPolicy
    {
        public function configure(): void
        {
            $this->reportOnly();
        }
    };

    config(['csp.policy' => $policy::class]);

    $headers = getResponseHeaders();

    expect($headers->get(CSP_HEADER))->toBeNull()
        ->and($headers->get(CSP_REPORT_HEADER))->not()->toBeNull();
});

it('can add multiple values for the same directive at the same time', function () {
    $policy = new class extends CspPolicy
    {
        public function configure(): void
        {
            $this->addDirective(CspDirective::Frame, ['src-1', 'src-2']);
        }
    };

    config(['csp.policy' => $policy::class]);

    $headers = getResponseHeaders();

    expect($headers->get(CSP_HEADER))->toEqual('frame-src src-1 src-2');
});

it('will automatically quote special directive values', function () {
    $policy = new class extends CspPolicy
    {
        public function configure(): void
        {
            $this->addDirective(CspDirective::Script, [CspKeyword::Self]);
        }
    };

    config(['csp.policy' => $policy::class]);

    $headers = getResponseHeaders();

    expect($headers->get(CSP_HEADER))->toEqual("script-src 'self'");
});

it('will automatically quote hashed values', function () {
    $policy = new class extends CspPolicy
    {
        public function configure(): void
        {
            $this->addDirective(CspDirective::Script, [
                'sha256-hash1',
                'sha384-hash2',
                'sha512-hash3',
            ]);
        }
    };

    config(['csp.policy' => $policy::class]);

    $headers = getResponseHeaders();

    expect($headers->get(CSP_HEADER))->toEqual("script-src 'sha256-hash1' 'sha384-hash2' 'sha512-hash3'");
});

it('will automatically check values when they are given in a single string separated by spaces', function () {
    $policy = new class extends CspPolicy
    {
        public function configure(): void
        {
            $this->addDirective(CspDirective::Script, 'sha256-hash1 ' . CspKeyword::Self->value . ' source');
        }
    };

    config(['csp.policy' => $policy::class]);

    $headers = getResponseHeaders();

    expect($headers->get(CSP_HEADER))->toEqual("script-src 'sha256-hash1' 'self' source");
});

it('will not output the same directive values twice', function () {
    $policy = new class extends CspPolicy
    {
        public function configure(): void
        {
            $this->addDirective(CspDirective::Script, [
                CspKeyword::Self,
                CspKeyword::Self,
            ]);
        }
    };

    config(['csp.policy' => $policy::class]);

    $headers = getResponseHeaders();

    expect($headers->get(CSP_HEADER))->toEqual("script-src 'self'");
});

test('route middleware will override global middleware for that route', function () {
    withoutExceptionHandling();

    $customPolicy = new class extends CspPolicy
    {
        public function configure(): void
        {
            $this->addDirective(CspDirective::Base, 'custom-policy');
        }
    };

    Route::get('other-route', fn () => 'ok')->middleware(AddCspHeaders::class . ':' . $customPolicy::class);

    $headers = getResponseHeaders('other-route');

    expect($headers->get(CSP_HEADER))->toEqual('base-uri custom-policy');
});

it('will handle scheme values', function () {
    $policy = new class extends CspPolicy
    {
        public function configure(): void
        {
            $this->addDirective(CspDirective::Img, [
                CspScheme::Data,
                CspScheme::Https,
                CspScheme::Ws,
                CspScheme::Wss,
            ]);
        }
    };

    config(['csp.policy' => $policy::class]);

    $headers = getResponseHeaders();

    expect($headers->get(CSP_HEADER))->toEqual('img-src data: https: ws: wss:');
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

    config(['csp.policy' => $policy::class]);

    $headers = getResponseHeaders();

    expect($headers->get(CSP_HEADER))->toEqual('upgrade-insecure-requests;block-all-mixed-content');
});

it('provides helper functions for empty value directives', function () {
    $policy = new class extends CspPolicy
    {
        public function configure(): void
        {
            $this
                ->upgradeInsecureRequests()
                ->blockAllMixedContent();
        }
    };

    config(['csp.policy' => $policy::class]);

    $headers = getResponseHeaders();

    expect($headers->get(CSP_HEADER))->toEqual('upgrade-insecure-requests;block-all-mixed-content');
});

it('can use a nonce value for a directive', function () {
    $nonce = csp_nonce();

    $policy = new class extends CspPolicy
    {
        public function configure(): void
        {
            $this->addNonceForDirective(CspDirective::Script);
        }
    };

    config(['csp.policy' => $policy::class]);

    $headers = getResponseHeaders();

    expect($headers->get(CSP_HEADER))->toEqual('script-src \'nonce-' . $nonce . '\'');
});
