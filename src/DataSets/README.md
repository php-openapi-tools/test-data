# OpenAPI data sets

This directory contains OpenAPI 3.x YAML fixtures and the [coverage plan](PLAN.md).

## Files in this directory

| Kind | Pattern | Example |
| --- | --- | --- |
| Focused fixture | `{Topic}.yaml` | `NestedReferenceSchema.yaml` |
| Composite fixture | `Mix{Theme}.yaml` | `MixCrudResource.yaml` (planned) |
| Documentation | `*.md` | `PLAN.md`, this file |

Only `*.yaml` files are loaded by `OpenAPITools\TestData\Provider`. Markdown files are documentation only.

## Focused vs composite

**Focused** files isolate one situation. When a generator test fails on `AllOfIntersection.yaml`, you know `allOf` handling broke.

**Composite** files stack many situations the way real-world specs do (CRUD + polymorphism + webhooks). They complement focused files; they do not replace them.

Every situation in [PLAN.md](PLAN.md) should have:

1. One focused YAML file
2. At least one composite file that also exercises it in context

## Authoring a focused fixture

1. Copy the boilerplate from `Basic.yaml` (`openapi`, `info`, `servers`, `paths`).
2. Change the minimum needed to trigger the behaviour under test.
3. Name the file in PascalCase matching the concern (`StringEnum.yaml`, not `enum-strings.yaml`).
4. Prefer OpenAPI **3.1.0** unless the situation is version-specific.
5. For schema-only tests, expose the schema via `GET /` with a response body referencing `#/components/schemas/…`.
6. Use the JSON array response wrapper where existing sets do (`schema: [$ref: "#/components/schemas/foo"]`).

## Authoring a composite fixture

1. Name it `Mix{Theme}.yaml` (e.g. `MixCrudResource.yaml`).
2. In [PLAN.md](PLAN.md), list the situation IDs it covers and which focused files it reuses patterns from.
3. Combine roughly 5–15 situations — enough to stress interactions, not so many that failures are hard to diagnose.
4. Use multiple paths, methods, and/or webhooks; composite sets should look like a small real API.

## Checklist for a new data set

- [ ] Create `{Name}.yaml` (or `Mix{Name}.yaml`) in this directory
- [ ] Update the **Current coverage** table in [PLAN.md](PLAN.md)
- [ ] If composite, add a row to the **Composite dataset catalog** in [PLAN.md](PLAN.md)
- [ ] Add `tests/DataTests/{Name}.php` in each downstream package that should assert behaviour
- [ ] Run `make contrib` in this repository

## Further reading

- [PLAN.md](PLAN.md) — full situation taxonomy, catalogs, and implementation phases
- [../../README.md](../../README.md) — package usage and PHPUnit integration
- [../../CONTRIBUTING.md](../../CONTRIBUTING.md) — QA and pull request workflow
