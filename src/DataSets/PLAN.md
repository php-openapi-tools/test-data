# OpenAPI test data plan

This document lists every OpenAPI situation PHPOpenAPITools should be able to handle, maps them to YAML data sets in this directory, and tracks what still needs to be added.

Downstream packages (`gatherer`, `generator-schema`, `generator-hydrator`, `generator-psr-15-webhook-middleware`, …) consume these files through `OpenAPITools\TestData\Provider` and assert expected representations or generated code in their own `tests/DataTests/` directories.

## Dataset strategy

Two complementary tiers of YAML files work together:

| Tier | Prefix / naming | Purpose | When a test fails |
| --- | --- | --- | --- |
| **Focused** | `{Topic}.yaml` | One situation (or the smallest inseparable pair) per file. Minimal paths, minimal schemas. | Pinpoints the exact feature that broke. |
| **Composite** | `Mix{Theme}.yaml` | Realistic combinations of many situations in one spec. Exercises interactions, naming collisions, and ordering. | Catches integration regressions focused files miss. |

**Rules of thumb**

- Every situation ID in sections 1–12 gets exactly **one focused file** (may be shared with a closely related ID, noted in the table).
- Every focused file should appear in **at least one** composite file so the situation is tested in isolation *and* in context.
- Composite files are **not** a substitute for focused files — they stack on top.
- Focused assertions should be strict (exact class names, property counts, alias lists). Composite assertions should be smoke-level (generates without error, key types exist) unless a interaction is the point of the mix.

## Conventions

| Rule | Detail |
| --- | --- |
| File name | PascalCase. Focused: `{Topic}.yaml`. Composite: `Mix{Theme}.yaml`. |
| OpenAPI version | Default to **3.1.0** unless the situation is version-specific (then add a suffix like `NullableKeyword30.yaml`). |
| Focused scope | One situation per file. Prefer the smallest spec that still triggers the behaviour. |
| Composite scope | Combine 5–15 situation IDs deliberately; document them in the composite catalog below. |
| Entry point | Focused schema sets: single `GET /` returning the schema under test. Composite sets: multiple operations and/or webhooks. |
| Response shape | When returning a component schema, use a **JSON array** wrapper (`schema: [$ref: …]`) where existing sets do, so array-return semantics stay consistent. |
| Metadata | Reuse the Petstore-style `info` / `license` / `servers` boilerplate from `Basic.yaml` unless the situation requires different document structure. |
| Assertions | Adding a YAML file here is not enough on its own — each consuming package needs a matching `tests/DataTests/{Name}.php` (or the package test is skipped). |

## Status legend

| Status | Meaning |
| --- | --- |
| ✅ Done | YAML exists and at least one downstream package has assertions. |
| 📄 YAML only | YAML exists; downstream `DataTests` still missing in some packages. |
| 📋 Planned | Not yet implemented; name is reserved. |
| ⏸ Deferred | Known edge case; intentionally out of scope for now. |

## Current coverage

| Data set | Primary concern | Status | gatherer | generator-schema | generator-hydrator | psr-15-webhook |
| --- | --- | --- | --- | --- | --- | --- |
| `Basic` | Minimal object schema, `$ref`, response headers | ✅ Done | ✅ | ✅ | ✅ | — |
| `ExampleData` | Scalar types, formats, patterns, arrays, nullable unions | ✅ Done | ✅ | ✅ | ✅ | — |
| `Aliases` | Structurally identical inline schemas → alias registration | ✅ Done | ✅ | ✅ | ✅ | — |
| `NestedSchema` | Inline nested objects (no `$ref`) | ✅ Done | ✅ | ✅ | ✅ | — |
| `NestedReferenceSchema` | Nested objects via `$ref` to components | ✅ Done | ✅ | ✅ | ✅ | — |
| `TripleNestedSchema` | Deep nesting with `$ref` on nested `schema` keyword | ✅ Done | ✅ | ✅ | ✅ | — |
| `DoubleUseOfTypes` | `type` array combined with `anyOf` on same property | 📄 YAML only | — | ✅ | ✅ | — |
| `BasicWebHooks` | Multiple webhooks, distinct payloads, header parameters | 📄 YAML only | — | — | ✅ | ✅ |
| `DiscriminatedWebHooks` | Webhook `oneOf` + `discriminator` | 📄 YAML only | — | — | ✅ | ✅ |
| `MultiVariantWebHooks` | Same event, multiple webhook variants (header discrimination) | 📄 YAML only | — | — | ✅ | ✅ |

---

## Focused dataset catalog

One file per situation (or inseparable pair). **Tier: focused.** Situation IDs refer to sections 1–12 below.

### Existing

| File | Situation IDs | Notes |
| --- | --- | --- |
| `Basic.yaml` | DOC-01, OP-01, OP-02, RES-01, RES-02, REF-01, REF-02, OBJ-01, TYP-02, NUL-01 | Baseline document. |
| `ExampleData.yaml` | TYP-01, TYP-03–TYP-10, ARR-01, ARR-02, CMP-03, EX-01 | Scalar/array kitchen sink for types. |
| `Aliases.yaml` | OBJ-06, OBJ-07, REG-01 | Inline structural duplication. |
| `NestedSchema.yaml` | OBJ-02 | Inline nesting only. |
| `NestedReferenceSchema.yaml` | OBJ-03, REF-03, REF-04, REG-03 | `$ref` nesting. |
| `TripleNestedSchema.yaml` | OBJ-04, OBJ-05, REF-05 | Deep + non-standard `schema` key. |
| `DoubleUseOfTypes.yaml` | NUL-02, CMP-08 | Overlapping type + anyOf. |
| `BasicWebHooks.yaml` | WH-01, WH-02, WH-03, OP-14 | Multi-event webhooks. |
| `DiscriminatedWebHooks.yaml` | WH-05 | Discriminator on webhook body. |
| `MultiVariantWebHooks.yaml` | WH-04 | Variant merge via hydrator. |

### Planned — document & operations

| File | Situation IDs |
| --- | --- |
| `MultipleServers.yaml` | DOC-02 |
| `ServerVariables.yaml` | DOC-03 |
| `WebHooksOnly.yaml` | DOC-04, WH-08 |
| `EmptyPaths.yaml` | DOC-06 |
| `TaggedOperations.yaml` | DOC-07 |
| `ExternalDocs.yaml` | DOC-08 |
| `OpenApi30.yaml` | DOC-09 |
| `PathParameters.yaml` | OP-03, OP-04 |
| `QueryParameters.yaml` | OP-05 |
| `QueryParameterStyles.yaml` | OP-06 |
| `HeaderParameters.yaml` | OP-07 |
| `CookieParameters.yaml` | OP-08 |
| `ParameterOneOf.yaml` | OP-09 |
| `RequestBody.yaml` | OP-10 |
| `OptionalRequestBody.yaml` | OP-11 |
| `MultipleRequestBodyContentTypes.yaml` | OP-12 |
| `HttpMethods.yaml` | OP-13 |
| `DeprecatedOperation.yaml` | OP-15 |
| `OperationSecurity.yaml` | OP-16 |

### Planned — responses

| File | Situation IDs |
| --- | --- |
| `MultipleResponseCodes.yaml` | RES-03 |
| `EmptyResponse.yaml` | RES-04 |
| `ErrorResponses.yaml` | RES-05 |
| `DefaultResponse.yaml` | RES-06 |
| `MultipleResponseContentTypes.yaml` | RES-07 |
| `InlineResponseSchema.yaml` | RES-08 |

### Planned — types & nullability

| File | Situation IDs |
| --- | --- |
| `DateTimeFormats.yaml` | TYP-11 |
| `BinaryFormats.yaml` | TYP-12 |
| `PasswordFormat.yaml` | TYP-13 |
| `StringConstraints.yaml` | TYP-14 |
| `NumericConstraints.yaml` | TYP-15 |
| `StringEnum.yaml` | TYP-16 |
| `IntEnum.yaml` | TYP-17 |
| `DefaultValues.yaml` | TYP-18 |
| `ConstValue.yaml` | TYP-19 |
| `ReadWriteOnly.yaml` | TYP-20 |
| `NullableOneOf.yaml` | NUL-03 |
| `NullableAnyOf.yaml` | NUL-04 |
| `NullableKeyword30.yaml` | NUL-05 |
| `RequiredNullable.yaml` | NUL-06 |

### Planned — objects, arrays, composition

| File | Situation IDs |
| --- | --- |
| `FreeFormObject.yaml` | OBJ-08 |
| `TypedMap.yaml` | OBJ-09 |
| `ClosedObject.yaml` | OBJ-10 |
| `EmptyObject.yaml` | OBJ-11 |
| `AllOptionalProperties.yaml` | OBJ-12 |
| `AwkwardPropertyNames.yaml` | OBJ-13 |
| `ArrayOfObjects.yaml` | ARR-03 |
| `NestedArrays.yaml` | ARR-04 |
| `ArrayConstraints.yaml` | ARR-05 |
| `UniqueItemsArray.yaml` | ARR-06 |
| `TupleSchema.yaml` | ARR-07 |
| `ArrayRootSchema.yaml` | ARR-08 |
| `AllOfIntersection.yaml` | CMP-01 |
| `AllOfWithRef.yaml` | CMP-02 |
| `OneOfObjects.yaml` | CMP-04 |
| `DiscriminatedOneOf.yaml` | CMP-05 |
| `DiscriminatorMapping.yaml` | CMP-06 |
| `AnyOfObjects.yaml` | CMP-07 |
| `NestedComposition.yaml` | CMP-09 |

### Planned — references, registry, metadata, webhooks

| File | Situation IDs |
| --- | --- |
| `RefWithSiblings.yaml` | REF-06 |
| `TransitiveRefs.yaml` | REF-07 |
| `AliasesAllowDuplication.yaml` | REG-02 |
| `AwkwardSchemaNames.yaml` | REG-04 |
| `SchemaExample.yaml` | EX-02 |
| `SchemaExamples.yaml` | EX-03 |
| `SchemaMetadata.yaml` | EX-04 |
| `DeprecatedSchema.yaml` | EX-05 |
| `WebHookMultipleContentTypes.yaml` | WH-06 |

**Focused total:** 10 done, 58 planned (+ 3 deferred with no file).

---

## Composite dataset catalog

**Tier: composite.** Each file deliberately stacks focused situations so generators must handle them together. The **Covers** column lists situation IDs; the **Reuses focused files** column names the isolated specs whose patterns appear in the mix.

| File | Theme | Covers (situation IDs) | Reuses patterns from |
| --- | --- | --- | --- |
| `MixSchemaBasics.yaml` | Schema generation breadth | TYP-01–TYP-10, ARR-01–ARR-02, OBJ-01–OBJ-03, NUL-01, EX-01 | `ExampleData`, `Basic`, `NestedSchema` |
| `MixNestingAndRefs.yaml` | Nesting × references × registry | OBJ-02–OBJ-07, REF-01–REF-05, REG-01, REG-03 | `NestedSchema`, `NestedReferenceSchema`, `TripleNestedSchema`, `Aliases` |
| `MixComposition.yaml` | All compositors in one API | CMP-01–CMP-09, REF-07, OBJ-06 | `AllOfIntersection`, `AllOfWithRef`, `OneOfObjects`, `AnyOfObjects`, `DiscriminatedOneOf`, `NestedComposition`, `DoubleUseOfTypes` |
| `MixNullability.yaml` | Every nullable encoding at once | NUL-01–NUL-06, CMP-08 | `NullableOneOf`, `NullableAnyOf`, `NullableKeyword30`, `RequiredNullable`, `DoubleUseOfTypes`, `AllOptionalProperties` |
| `MixCrudResource.yaml` | Full REST resource lifecycle | OP-03–OP-05, OP-10–OP-13, RES-03–RES-05, RES-08, TYP-16, ARR-03 | `PathParameters`, `QueryParameters`, `RequestBody`, `HttpMethods`, `MultipleResponseCodes`, `ErrorResponses`, `StringEnum`, `ArrayOfObjects` |
| `MixParameterSurface.yaml` | All parameter locations & styles | OP-05–OP-09, OP-06, RES-01 | `QueryParameters`, `QueryParameterStyles`, `HeaderParameters`, `CookieParameters`, `PathParameters`, `ParameterOneOf` |
| `MixResponseSurface.yaml` | Response shape permutations | RES-01–RES-08, REF-02 | `Basic`, `MultipleResponseCodes`, `EmptyResponse`, `DefaultResponse`, `MultipleResponseContentTypes`, `InlineResponseSchema`, `ErrorResponses` |
| `MixMapsAndObjects.yaml` | Object openness & maps | OBJ-08–OBJ-13, ARR-03, ARR-04 | `FreeFormObject`, `TypedMap`, `ClosedObject`, `EmptyObject`, `AwkwardPropertyNames`, `ArrayOfObjects` |
| `MixArrayShapes.yaml` | Array variants together | ARR-01–ARR-08, TYP-01 | `ExampleData`, `ArrayOfObjects`, `NestedArrays`, `ArrayConstraints`, `UniqueItemsArray`, `TupleSchema`, `ArrayRootSchema` |
| `MixInheritanceApi.yaml` | allOf + discriminator + refs (Stripe/GitHub style) | CMP-01, CMP-02, CMP-05, CMP-06, REF-03, REF-07, OBJ-03 | `AllOfWithRef`, `DiscriminatedOneOf`, `DiscriminatorMapping`, `TransitiveRefs`, `NestedReferenceSchema` |
| `MixIssueTracker.yaml` | Jira/Linear-style field values | CMP-08, NUL-02, NUL-03, OBJ-12, TYP-09 | `DoubleUseOfTypes`, `NullableOneOf`, `AllOptionalProperties` |
| `MixWebHooks.yaml` | All webhook patterns in one spec | WH-01–WH-06, WH-08, DOC-04 | `BasicWebHooks`, `DiscriminatedWebHooks`, `MultiVariantWebHooks`, `WebHookMultipleContentTypes`, `WebHooksOnly` |
| `MixPathsAndWebHooks.yaml` | REST + webhooks coexisting | DOC-05, WH-01, WH-05, OP-01, OP-10 | `Basic`, `BasicWebHooks`, `DiscriminatedWebHooks`, `RequestBody` |
| `MixMetadata.yaml` | Examples, titles, deprecation | EX-02–EX-05, DOC-07, DOC-08, OP-15 | `SchemaExample`, `SchemaExamples`, `SchemaMetadata`, `DeprecatedSchema`, `TaggedOperations`, `ExternalDocs`, `DeprecatedOperation` |
| `MixOpenApi30Compat.yaml` | 3.0-only constructs beside 3.1 patterns | DOC-09, NUL-05, CMP-01 | `OpenApi30`, `NullableKeyword30`, `AllOfIntersection` |
| `MixAwkwardNames.yaml` | Name sanitization stress test | OBJ-13, REG-04, OP-14, REF-03 | `AwkwardPropertyNames`, `AwkwardSchemaNames`, `BasicWebHooks`, `NestedReferenceSchema` |
| `MixRegistryStress.yaml` | Dedup vs duplication configuration | REG-01–REG-04, OBJ-06, OBJ-07, REF-03 | `Aliases`, `AliasesAllowDuplication`, `AwkwardSchemaNames`, `NestedReferenceSchema` |
| `MixPetstore.yaml` | Classic tutorial API (integration smoke) | DOC-01, OP-03, OP-05, OP-10, OP-13, RES-03, OBJ-02, OBJ-03, TYP-16, ARR-03, CMP-05 | Subset of `MixCrudResource` + `DiscriminatedOneOf` + nesting focused files |

**Composite total:** 0 done, 18 planned.

### Coverage matrix (focused → composite)

Each focused file should appear in at least one composite. Planned pairings:

| Focused file | Composite file(s) |
| --- | --- |
| `Basic.yaml` | MixSchemaBasics, MixResponseSurface, MixPathsAndWebHooks, MixPetstore |
| `ExampleData.yaml` | MixSchemaBasics, MixArrayShapes |
| `Aliases.yaml` | MixNestingAndRefs, MixComposition, MixRegistryStress |
| `NestedSchema.yaml` | MixSchemaBasics, MixNestingAndRefs, MixPetstore |
| `NestedReferenceSchema.yaml` | MixNestingAndRefs, MixInheritanceApi, MixAwkwardNames, MixRegistryStress |
| `TripleNestedSchema.yaml` | MixNestingAndRefs |
| `DoubleUseOfTypes.yaml` | MixComposition, MixNullability, MixIssueTracker |
| `BasicWebHooks.yaml` | MixWebHooks, MixPathsAndWebHooks |
| `DiscriminatedWebHooks.yaml` | MixWebHooks, MixPathsAndWebHooks |
| `MultiVariantWebHooks.yaml` | MixWebHooks |
| `AllOfIntersection.yaml` | MixComposition, MixInheritanceApi, MixOpenApi30Compat |
| `DiscriminatedOneOf.yaml` | MixComposition, MixInheritanceApi, MixPetstore |
| `PathParameters.yaml` | MixCrudResource, MixParameterSurface, MixPetstore |
| `NullableKeyword30.yaml` | MixNullability, MixOpenApi30Compat |
| *(all other planned focused files)* | See **Reuses patterns from** column in composite catalog |

---

## 1. Document structure

Situations around the top-level OpenAPI document, independent of individual schemas.

| ID | Situation | Focused file | Composite file(s) | Status | Notes |
| --- | --- | --- | --- | --- | --- |
| DOC-01 | Minimal valid document (info + paths) | `Basic.yaml` | MixSchemaBasics, MixPetstore | ✅ Done | Baseline. |
| DOC-02 | Multiple `servers` entries | `MultipleServers.yaml` | MixPetstore | 📋 Planned | URL selection / default server. |
| DOC-03 | Server URL variables `{var}` | `ServerVariables.yaml` | — | 📋 Planned | Template expansion. |
| DOC-04 | `webhooks` only (no `paths`) | `WebHooksOnly.yaml` | MixWebHooks | 📋 Planned | Webhook-first APIs. |
| DOC-05 | Both `paths` and `webhooks` | `PathsAndWebHooks.yaml`¹ | MixPathsAndWebHooks | 📋 Planned | ¹Composite-only; no separate focused file needed. |
| DOC-06 | Empty `paths` object | `EmptyPaths.yaml` | — | 📋 Planned | Schema-only gathering. |
| DOC-07 | `tags` with operations referencing them | `TaggedOperations.yaml` | MixMetadata | 📋 Planned | Tag metadata propagation. |
| DOC-08 | `externalDocs` on operation and schema | `ExternalDocs.yaml` | MixMetadata | 📋 Planned | Documentation links. |
| DOC-09 | OpenAPI **3.0.x** document (`openapi: 3.0.3`) | `OpenApi30.yaml` | MixOpenApi30Compat | 📋 Planned | Version-specific parsing. |
| DOC-10 | OpenAPI **3.1.x** document (`openapi: 3.1.0`) | all current sets | all composite sets | ✅ Done | Default target. |

---

## 2. Paths and operations

HTTP surface area beyond a single `GET /`.

| ID | Situation | Focused file | Composite file(s) | Status | Notes |
| --- | --- | --- | --- | --- | --- |
| OP-01 | Single `GET` on `/` | `Basic.yaml` | MixSchemaBasics, MixPathsAndWebHooks | ✅ Done | |
| OP-02 | Root path `/` → `Root` class name | `Basic.yaml` | MixSchemaBasics | ✅ Done | Exercised by gatherer. |
| OP-03 | Path with segments (`/users/{id}`) | `PathParameters.yaml` | MixCrudResource, MixParameterSurface, MixPetstore | 📋 Planned | Path template + parameter binding. |
| OP-04 | Path parameter required / typed | `PathParameters.yaml` | MixCrudResource, MixParameterSurface | 📋 Planned | |
| OP-05 | Query parameters (scalar) | `QueryParameters.yaml` | MixCrudResource, MixParameterSurface, MixPetstore | 📋 Planned | |
| OP-06 | Query parameters (array, `style` / `explode`) | `QueryParameterStyles.yaml` | MixParameterSurface | 📋 Planned | form, spaceDelimited, pipeDelimited, deepObject. |
| OP-07 | Header parameters on operation | `HeaderParameters.yaml` | MixParameterSurface | 📋 Planned | Distinct from response headers. |
| OP-08 | Cookie parameters | `CookieParameters.yaml` | MixParameterSurface | 📋 Planned | |
| OP-09 | Parameter with `oneOf` schema | `ParameterOneOf.yaml` | MixParameterSurface | 📋 Planned | gatherer already handles this in `Operation::gather`. |
| OP-10 | `POST` with required `requestBody` | `RequestBody.yaml` | MixCrudResource, MixPathsAndWebHooks | 📋 Planned | |
| OP-11 | `requestBody` optional | `OptionalRequestBody.yaml` | MixCrudResource | 📋 Planned | |
| OP-12 | Multiple `requestBody` content types | `MultipleRequestBodyContentTypes.yaml` | MixResponseSurface | 📋 Planned | e.g. JSON + form-urlencoded. |
| OP-13 | `PUT` / `PATCH` / `DELETE` operations | `HttpMethods.yaml` | MixCrudResource, MixPetstore | 📋 Planned | Method-specific generation. |
| OP-14 | `operationId` with slashes (`foo/bar`) | `BasicWebHooks.yaml` | MixWebHooks, MixAwkwardNames | ✅ Done | Used for webhook event parsing. |
| OP-15 | Deprecated operation | `DeprecatedOperation.yaml` | MixMetadata | 📋 Planned | |
| OP-16 | Operation-level security requirements | `OperationSecurity.yaml` | — | 📋 Planned | |
| OP-17 | Callbacks on operation | — | — | ⏸ Deferred | Rare in target clients; add when needed. |

---

## 3. Responses

| ID | Situation | Proposed file | Status | Notes |
| --- | --- | --- | --- | --- |
| RES-01 | `200` with JSON body + response headers | `Basic.yaml` | ✅ Done | |
| RES-02 | Response body as `$ref` array (tuple-style list) | `Basic.yaml` | ✅ Done | `schema: [$ref: …]`. |
| RES-03 | Multiple success status codes (`200`, `201`, `204`) | `MultipleResponseCodes.yaml` | 📋 Planned | |
| RES-04 | Empty response (no content) | `EmptyResponse.yaml` | 📋 Planned | `204` / `EmptyResponse` representation. |
| RES-05 | Error responses (`4xx`, `5xx`) with schemas | `ErrorResponses.yaml` | 📋 Planned | Throwable schema registry. |
| RES-06 | `default` response | `DefaultResponse.yaml` | 📋 Planned | |
| RES-07 | Multiple response content types | `MultipleResponseContentTypes.yaml` | 📋 Planned | |
| RES-08 | Response schema inline (no `$ref`) | `InlineResponseSchema.yaml` | 📋 Planned | |

---

## 4. Scalar and simple types

Primitive JSON Schema types as schema properties or standalone component schemas.

| ID | Situation | Proposed file | Status | Notes |
| --- | --- | --- | --- | --- |
| TYP-01 | `string` (plain) | `ExampleData.yaml` | ✅ Done | |
| TYP-02 | `string` + `format: uuid` | `Basic.yaml` | ✅ Done | |
| TYP-03 | `string` + `format: uri` | `ExampleData.yaml` | ✅ Done | |
| TYP-04 | `string` + `format: email` | `ExampleData.yaml` | ✅ Done | |
| TYP-05 | `string` + `format: date-time` | `ExampleData.yaml` | ✅ Done | |
| TYP-06 | `string` + `format: ipv4` / `ipv6` | `ExampleData.yaml` | ✅ Done | |
| TYP-07 | `string` + `pattern` | `ExampleData.yaml` | ✅ Done | SSN-style regex. |
| TYP-08 | `integer` / `number` / `boolean` | `ExampleData.yaml` | ✅ Done | Uses shorthand `int`, `float`, `bool`. |
| TYP-09 | `integer` \| `float` union via `oneOf` | `ExampleData.yaml` | ✅ Done | `float-int` property. |
| TYP-10 | Empty property schema (`{}`) | `ExampleData.yaml` | ✅ Done | `niks: {}`. |
| TYP-11 | `string` + `format: date` / `time` | `DateTimeFormats.yaml` | 📋 Planned | |
| TYP-12 | `string` + `format: byte` / `binary` | `BinaryFormats.yaml` | 📋 Planned | |
| TYP-13 | `string` + `format: password` | `PasswordFormat.yaml` | 📋 Planned | |
| TYP-14 | `string` + `minLength` / `maxLength` | `StringConstraints.yaml` | 📋 Planned | |
| TYP-15 | Numeric `minimum` / `maximum` / `multipleOf` | `NumericConstraints.yaml` | 📋 Planned | |
| TYP-16 | `enum` (string) | `StringEnum.yaml` | 📋 Planned | |
| TYP-17 | `enum` (int) | `IntEnum.yaml` | 📋 Planned | |
| TYP-18 | `default` on property | `DefaultValues.yaml` | 📋 Planned | |
| TYP-19 | `const` (3.1) | `ConstValue.yaml` | 📋 Planned | Single allowed value. |
| TYP-20 | `readOnly` / `writeOnly` properties | `ReadWriteOnly.yaml` | 📋 Planned | |

---

## 5. Nullability

OpenAPI 3.0 and 3.1 express nullability differently; both need coverage.

| ID | Situation | Proposed file | Status | Notes |
| --- | --- | --- | --- | --- |
| NUL-01 | Optional property (not in `required`) | `Basic.yaml` | ✅ Done | Implicit nullable in gatherer. |
| NUL-02 | 3.1 type array: `[string, "null"]` | `DoubleUseOfTypes.yaml` | ✅ Done | On `value` property. |
| NUL-03 | 3.1 `oneOf` with `null` (2-branch shortcut) | `NullableOneOf.yaml` | 📋 Planned | gatherer unwraps to non-null branch. |
| NUL-04 | 3.1 `anyOf` with `null` (2-branch shortcut) | `NullableAnyOf.yaml` | 📋 Planned | Same unwrap logic as `oneOf`. |
| NUL-05 | 3.0 `nullable: true` keyword | `NullableKeyword30.yaml` | 📋 Planned | Requires `openapi: 3.0.3`. |
| NUL-06 | Required property that is also nullable | `RequiredNullable.yaml` | 📋 Planned | Contradiction handling / edge case. |

---

## 6. Objects

| ID | Situation | Proposed file | Status | Notes |
| --- | --- | --- | --- | --- |
| OBJ-01 | Object with `required` properties | `Basic.yaml` | ✅ Done | |
| OBJ-02 | Inline nested object | `NestedSchema.yaml` | ✅ Done | |
| OBJ-03 | Nested object via `$ref` | `NestedReferenceSchema.yaml` | ✅ Done | |
| OBJ-04 | Deep nesting (3+ levels) with refs | `TripleNestedSchema.yaml` | ✅ Done | Uses nested `schema` keyword. |
| OBJ-05 | `$ref` nested under property `schema` key | `TripleNestedSchema.yaml` | ✅ Done | Non-standard placement seen in the wild. |
| OBJ-06 | Structurally identical inline objects → aliases | `Aliases.yaml` | ✅ Done | First wins; rest become aliases. |
| OBJ-07 | Component schema name colliding with type semantics | `Aliases.yaml` | ✅ Done | Schema named `string`. |
| OBJ-08 | `additionalProperties: true` (free-form map) | `FreeFormObject.yaml` | 📋 Planned | |
| OBJ-09 | `additionalProperties: { schema }` (typed map) | `TypedMap.yaml` | 📋 Planned | `map<string, Foo>`. |
| OBJ-10 | `additionalProperties: false` (closed object) | `ClosedObject.yaml` | 📋 Planned | |
| OBJ-11 | Empty object (no properties) | `EmptyObject.yaml` | 📋 Planned | |
| OBJ-12 | Object with only optional properties | `AllOptionalProperties.yaml` | 📋 Planned | Example generation skips unset keys. |
| OBJ-13 | Property name requiring sanitization for PHP | `AwkwardPropertyNames.yaml` | 📋 Planned | e.g. `$ref`-like names, unicode, reserved words. |

---

## 7. Arrays

| ID | Situation | Proposed file | Status | Notes |
| --- | --- | --- | --- | --- |
| ARR-01 | `array` of `string` | `ExampleData.yaml` | ✅ Done | `string-array`. |
| ARR-02 | `array` of `number` | `ExampleData.yaml` | ✅ Done | `float-array`. |
| ARR-03 | `array` of objects (`$ref` items) | `ArrayOfObjects.yaml` | 📋 Planned | |
| ARR-04 | Nested arrays | `NestedArrays.yaml` | 📋 Planned | |
| ARR-05 | `minItems` / `maxItems` | `ArrayConstraints.yaml` | 📋 Planned | Affects example data length. |
| ARR-06 | `uniqueItems: true` | `UniqueItemsArray.yaml` | 📋 Planned | |
| ARR-07 | Tuple / `prefixItems` (3.1) | `TupleSchema.yaml` | 📋 Planned | Heterogeneous array items. |
| ARR-08 | Top-level schema with `type: array` | `ArrayRootSchema.yaml` | 📋 Planned | `isArray` flag on representation. |

---

## 8. Composition (`allOf` / `oneOf` / `anyOf` / `not`)

| ID | Situation | Proposed file | Status | Notes |
| --- | --- | --- | --- | --- |
| CMP-01 | `allOf` merging multiple object schemas | `AllOfIntersection.yaml` | 📋 Planned | → multiple contracts on one schema. |
| CMP-02 | `allOf` with `$ref` + inline properties | `AllOfWithRef.yaml` | 📋 Planned | Common inheritance pattern. |
| CMP-03 | `oneOf` scalar union (2+ non-null branches) | `ExampleData.yaml` | ✅ Done | `float-int`. |
| CMP-04 | `oneOf` object union (no discriminator) | `OneOfObjects.yaml` | 📋 Planned | |
| CMP-05 | `oneOf` + `discriminator` | `DiscriminatedOneOf.yaml` | 📋 Planned | Non-webhook context. |
| CMP-06 | `oneOf` + `discriminator.mapping` overrides | `DiscriminatorMapping.yaml` | 📋 Planned | |
| CMP-07 | `anyOf` object union | `AnyOfObjects.yaml` | 📋 Planned | |
| CMP-08 | `type` array **and** `anyOf` on same property | `DoubleUseOfTypes.yaml` | ✅ Done | Jira-style issue field value. |
| CMP-09 | Nested composition (`oneOf` inside `allOf`) | `NestedComposition.yaml` | 📋 Planned | |
| CMP-10 | `not` schema | `NotSchema.yaml` | ⏸ Deferred | Limited generator support expected. |

---

## 9. References (`$ref`)

| ID | Situation | Proposed file | Status | Notes |
| --- | --- | --- | --- | --- |
| REF-01 | Property `$ref` to `#/components/schemas/…` | `Basic.yaml` | ✅ Done | |
| REF-02 | Response `$ref` as single-element array | `Basic.yaml` | ✅ Done | |
| REF-03 | Repeated `$ref` to same component | `NestedReferenceSchema.yaml` | ✅ Done | `string` reused four times. |
| REF-04 | `$ref` only resolved after full registry pass | `NestedReferenceSchema.yaml` | ✅ Done | Two-pass gather in `Gatherer`. |
| REF-05 | Inline schema registered as unknown, then gathered | `TripleNestedSchema.yaml` | ✅ Done | Unknown-schema loop. |
| REF-06 | `$ref` sibling keywords (3.1 overlay) | `RefWithSiblings.yaml` | 📋 Planned | 3.1 allows keywords next to `$ref`. |
| REF-07 | Transitive `$ref` chains (A → B → C) | `TransitiveRefs.yaml` | 📋 Planned | |
| REF-08 | Circular `$ref` (A → B → A) | `CircularRefs.yaml` | ⏸ Deferred | May require explicit error handling. |
| REF-09 | External file `$ref` | `ExternalRef.yaml` | ⏸ Deferred | Needs multi-file fixture directory. |

---

## 10. Webhooks

| ID | Situation | Proposed file | Status | Notes |
| --- | --- | --- | --- | --- |
| WH-01 | Single webhook, JSON body | `BasicWebHooks.yaml` | ✅ Done | `ping` webhook. |
| WH-02 | Multiple webhooks, different events | `BasicWebHooks.yaml` | ✅ Done | `ping` + `push`. |
| WH-03 | Webhook header parameters | `BasicWebHooks.yaml` | ✅ Done | `X-Event-Type`. |
| WH-04 | Multiple webhooks sharing event via `operationId` prefix | `MultiVariantWebHooks.yaml` | ✅ Done | Hydrator merges variants. |
| WH-05 | Discriminated `oneOf` request body | `DiscriminatedWebHooks.yaml` | ✅ Done | `eventType` discriminator. |
| WH-06 | Webhook with multiple content types | `WebHookMultipleContentTypes.yaml` | 📋 Planned | |
| WH-07 | Webhook missing request body (invalid) | — | ⏸ Deferred | gatherer skips / throws; error-path test, not a YAML set. |
| WH-08 | Webhook-only spec (no paths) | `WebHooksOnly.yaml` | 📋 Planned | See DOC-04. |

---

## 11. Schema registry and deduplication

Configuration-driven behaviour (`Gathering\Schemas`) rather than spec syntax alone.

| ID | Situation | Proposed file | Status | Notes |
| --- | --- | --- | --- | --- |
| REG-01 | Duplicate inline schemas → aliases enabled | `Aliases.yaml` | ✅ Done | `useAliasesForDuplication: true`. |
| REG-02 | Duplicate inline schemas → duplication allowed | `AliasesAllowDuplication.yaml` | 📋 Planned | `allowDuplication: true`; expect separate classes. |
| REG-03 | Same `$ref` used for different properties | `NestedReferenceSchema.yaml` | ✅ Done | Shared `string` schema. |
| REG-04 | Class name derivation from awkward component names | `AwkwardSchemaNames.yaml` | 📋 Planned | kebab-case, dots, slashes. |

---

## 12. Example and metadata propagation

| ID | Situation | Proposed file | Status | Notes |
| --- | --- | --- | --- | --- |
| EX-01 | Property-level implicit example from type | `ExampleData.yaml` | ✅ Done | ExampleData gatherer. |
| EX-02 | Schema-level `example` object | `SchemaExample.yaml` | 📋 Planned | |
| EX-03 | Schema-level `examples` map | `SchemaExamples.yaml` | 📋 Planned | |
| EX-04 | `title` / `description` on schema | `SchemaMetadata.yaml` | 📋 Planned | |
| EX-05 | `deprecated: true` on schema or property | `DeprecatedSchema.yaml` | 📋 Planned | |

---

## Recommended implementation order

Work in phases so each phase unlocks the next layer of generator coverage.

### Phase 1 — Close gaps in existing areas

1. `AllOfIntersection.yaml` — no `allOf` coverage yet; gatherer has full support.
2. `OneOfObjects.yaml` / `AnyOfObjects.yaml` — object unions beyond scalars.
3. `DiscriminatedOneOf.yaml` — discriminator outside webhooks.
4. Add gatherer `DataTests` for `DoubleUseOfTypes`, webhook sets.

### Phase 2 — Operations and parameters

5. `PathParameters.yaml`
6. `QueryParameters.yaml`
7. `RequestBody.yaml`
8. `MultipleResponseCodes.yaml` + `EmptyResponse.yaml` + `ErrorResponses.yaml`

### Phase 3 — Types and constraints

9. `StringEnum.yaml` / `IntEnum.yaml`
10. `NullableKeyword30.yaml` (3.0 compatibility)
11. `FreeFormObject.yaml` / `TypedMap.yaml`
12. `ArrayOfObjects.yaml` / `TupleSchema.yaml`

### Phase 4 — Edge cases

13. `RefWithSiblings.yaml`
14. `AwkwardPropertyNames.yaml` / `AwkwardSchemaNames.yaml`
15. `MultipleRequestBodyContentTypes.yaml` / `MultipleResponseContentTypes.yaml`

---

## Adding a new data set

1. Create `src/DataSets/{PascalCaseName}.yaml` following the conventions above.
2. Run consuming package tests — `Provider::sets()` picks up new `*.yaml` files automatically (markdown is ignored).
3. Add `tests/DataTests/{PascalCaseName}.php` in each package that should assert behaviour (copy an existing `DataTests` class as template).
4. Update the **Current coverage** table in this file.
5. Open a PR; `make contrib` must pass in this repository.

See also [`README.md`](README.md) (this directory) and the [package README](../../README.md).

---

## Out of scope (for now)

These are valid OpenAPI situations but not current priorities for PHPOpenAPITools client generation:

- OpenAPI **2.0** (Swagger) specs
- Full JSON Schema **2020-12** keyword surface (unevaluatedProperties, if/then/else, …) beyond what OpenAPI 3.1 embeds
- Multi-document specs with external `$ref` files (needs fixture directory layout)
- OAuth flow objects / security scheme generation
- Link objects and runtime link following
- XML, multipart, or non-JSON content types (unless explicitly adding content-type support)

When any of these become requirements, add a row to the relevant section above before implementing.
