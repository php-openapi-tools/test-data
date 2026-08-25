# test-data

Shared OpenAPI 3.x fixtures for [PHPOpenAPITools](https://github.com/php-api-clients) packages. Each YAML file describes a real (or realistic) API fragment so `gatherer`, code generators, and related tools can be tested against the same specs.

## Installation

```bash
composer require --dev openapi-tools/test-data
```

Downstream packages already depend on this library in their `require-dev` section.

## Usage

`Provider::sets()` discovers every `*.yaml` file in `src/DataSets/` and yields it as a `DataSet` for PHPUnit:

```php
use OpenAPITools\TestData\DataSet;
use OpenAPITools\TestData\Provider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;

final class MyGeneratorTest extends TestCase
{
    #[Test]
    #[DataProviderExternal(Provider::class, 'sets')]
    public function generate(DataSet $dataSet): void
    {
        $spec = Reader::readFromYamlFile($dataSet->fileName);
        // … exercise your code against $spec …
    }
}
```

Each yielded entry looks like `'Basic' => [new DataSet('Basic', '/path/to/Basic.yaml')]`, which matches PHPUnit’s data-provider shape.

If a consuming package has no matching assertion class for a data set, its test typically skips that set (see **Downstream assertions** below).

## Data sets

Fixtures live in [`src/DataSets/`](src/DataSets/). There are two tiers:

| Tier | File pattern | Purpose |
| --- | --- | --- |
| **Focused** | `{Topic}.yaml` | One OpenAPI situation per file — smallest spec that triggers a single behaviour. |
| **Composite** | `Mix{Theme}.yaml` | Several situations combined — catches interaction bugs between features. |

### Available focused sets

| Name | What it exercises |
| --- | --- |
| `Basic` | Minimal object schema, `$ref`, response headers, root path |
| `ExampleData` | Scalar types, formats, patterns, arrays, scalar `oneOf` |
| `Aliases` | Structurally identical inline schemas registered as aliases |
| `NestedSchema` | Inline nested objects |
| `NestedReferenceSchema` | Nested objects via `$ref` |
| `TripleNestedSchema` | Deep nesting with `$ref` on a nested `schema` keyword |
| `DoubleUseOfTypes` | `type` array combined with `anyOf` on the same property |
| `BasicWebHooks` | Multiple webhooks, distinct payloads, header parameters |
| `DiscriminatedWebHooks` | Webhook body as discriminated `oneOf` |
| `MultiVariantWebHooks` | Same event, multiple webhook variants |

Composite sets (`Mix*.yaml`) are planned — see the [coverage plan](src/DataSets/PLAN.md).

## Coverage plan

The full taxonomy of OpenAPI situations, planned fixtures, composite mixes, and implementation status lives in:

**[`src/DataSets/PLAN.md`](src/DataSets/PLAN.md)**

Use that document when adding a new situation: pick a focused file name, note which composite files should reuse it, and update the status tables.

## Downstream assertions

YAML in this repository is only half the contract. Packages that consume test-data implement expectations in their own tree:

```
gatherer/tests/DataTests/{Name}.php
generator-schema/tests/DataTests/{Name}.php
generator-hydrator/tests/DataTests/{Name}.php
generator-psr-15-webhook-middleware/tests/DataTests/{Name}.php
```

Example (`gatherer`):

```php
final class Basic
{
    /** @api */
    public static function assert(Representation $representation): void
    {
        Assert::assertCount(2, $representation->schemas);
        // …
    }
}
```

Generator tests call `{Name}::assert(...)` on generated files; gatherer tests assert on the `Representation` object. Without a `DataTests` class, the test run skips that data set.

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for QA commands and the workflow for adding a new data set.

## License

MIT — see [LICENSE](LICENSE).
