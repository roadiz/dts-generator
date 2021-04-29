<?php
declare(strict_types=1);

namespace RZ\Roadiz\Typescript\Declaration\Generators;

final class EnumFieldGenerator extends AbstractFieldGenerator
{
    protected function getType(): string
    {
        switch (true) {
            case $this->field->isEnum():
                return 'string';
            case $this->field->isMultiple():
                return 'Array<string>';
            default:
                return 'any';
        }
    }

    protected function getIntroductionLines(): array
    {
        $lines = parent::getIntroductionLines();
        if (!empty($this->field->getDefaultValues())) {
            $lines[] = 'Possible values: ' . $this->field->getDefaultValues();
        }
        return $lines;
    }
}
