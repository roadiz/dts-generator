# dts-generator
Roadiz sub-package which generates Typescript interface declaration skeleton based on your schema.

### Usage

```php
use RZ\Roadiz\Contracts\NodeType\NodeTypeInterface;
use RZ\Roadiz\Typescript\Declaration\DeclarationGeneratorFactory;
use RZ\Roadiz\Typescript\Declaration\Generators\DeclarationGenerator;
use Symfony\Component\HttpFoundation\ParameterBag;

/** @var ParameterBag<NodeTypeInterface> $nodeTypesBag */
$nodeTypesBag = $serviceContainer->get('nodeTypesBag');

$declarationFactory = new DeclarationGeneratorFactory($nodeTypesBag);
$declaration = new DeclarationGenerator($declarationFactory);

echo $declaration->getContents();
```
