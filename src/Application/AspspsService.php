<?php
declare(strict_types=1);

namespace Psd2\Application;


use Psd2\Domain\StringInvoker;

final class AspspsService
{
    /**
     * @var StringInvoker
     */
    private $aspsps;

    public function __construct(StringInvoker $aspsps)
    {
        $this->aspsps = $aspsps;
    }

    public function getAspsps(): string
    {
        return $this->aspsps->__invoke();
    }
}
