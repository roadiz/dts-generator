<?php
declare(strict_types=1);

namespace RZ\Roadiz\Typescript\Declaration\Generators;

use RZ\Roadiz\Contracts\NodeType\NodeTypeFieldInterface;
use RZ\Roadiz\Contracts\NodeType\NodeTypeInterface;
use RZ\Roadiz\Typescript\Declaration\DeclarationGeneratorFactory;

final class NodeTypeGenerator
{
    private NodeTypeInterface $nodeType;
    /**
     * @var array<AbstractFieldGenerator>
     */
    private array $fieldGenerators;
    private DeclarationGeneratorFactory $generatorFactory;

    /**
     * @param NodeTypeInterface $nodeType
     * @param DeclarationGeneratorFactory $generatorFactory
     */
    public function __construct(
        NodeTypeInterface $nodeType,
        DeclarationGeneratorFactory $generatorFactory
    ) {
        $this->nodeType = $nodeType;
        $this->fieldGenerators = [];
        $this->generatorFactory = $generatorFactory;

        /** @var NodeTypeFieldInterface $field */
        foreach ($this->nodeType->getFields() as $field) {
            $this->fieldGenerators[] = $this->generatorFactory->createForNodeTypeField($field);
        }
    }

    public function getContents(): string
    {
        /*
         * interface NSPage extends RoadizNodesSources {
         *     image: Array<RoadizDocument>
         *     head: NSPageHead
         *     headerImage: Array<RoadizDocument>
         *     excerpt: string
         *     linkLabel: string
         *     linkUrl: string
         *     linkReference: Array<RoadizNodesSources>
         *     linkDownload: Array<RoadizDocument>
         *     color: string
         * }
         */
        return implode("\n", [
            $this->getIntroduction(),
            $this->getInterfaceBody()
        ]);
    }

    protected function getInterfaceBody(): string
    {
        $lines = [
            'interface ' . $this->nodeType->getSourceEntityClassName() . ' extends RoadizNodesSources {',
            $this->getFieldsContents(),
            '}'
        ];

        return implode("\n", $lines);
    }

    protected function getIntroduction(): string
    {
        $lines = [
            $this->nodeType->getLabel(),
            $this->nodeType->getName(),
        ];
        if (!empty($this->nodeType->getDescription())) {
            $lines[] = $this->nodeType->getDescription();
        }

        $lines[] = 'Publishable: ' . $this->generatorFactory->getHumanBool($this->nodeType->isPublishable());
        $lines[] = 'Visible: ' . $this->generatorFactory->getHumanBool($this->nodeType->isVisible());

        return implode("\n", array_map(function (string $line) {
            return '// ' . $line;
        }, $lines));
    }

    protected function getFieldsContents(): string
    {
        return implode("\n", array_map(function (AbstractFieldGenerator $abstractFieldGenerator) {
            return $abstractFieldGenerator->getContents();
        }, $this->fieldGenerators));
    }
}
