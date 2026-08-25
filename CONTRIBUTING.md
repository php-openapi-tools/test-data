# Contributing

Pull requests are highly appreciated. Here's a quick guide.

## Adding or changing a data set

OpenAPI fixtures live in [`src/DataSets/`](src/DataSets/). Read [`src/DataSets/PLAN.md`](src/DataSets/PLAN.md) before adding a new one — it lists every situation we want to cover, which focused file owns each situation, and which composite files should mix them.

### Focused data set (one situation)

1. Add `src/DataSets/{PascalCaseName}.yaml`. Keep it minimal; one concern per file.
2. Mark the situation as done in **PLAN.md** (focused catalog and relevant taxonomy section).
3. Add or update `tests/DataTests/{PascalCaseName}.php` in downstream packages (`gatherer`, `generator-schema`, `generator-hydrator`, …) when you need assertions there. A YAML-only change is fine if another package will add tests later — note **YAML only** status in PLAN.md.

### Composite data set (mixed situations)

1. Add `src/DataSets/Mix{Theme}.yaml`.
2. Document it in the **Composite dataset catalog** in PLAN.md (situation IDs covered, focused files reused).
3. Add smoke-level `DataTests` in at least one generator package.

### Conventions (summary)

- OpenAPI 3.1.0 by default; suffix with `30` for 3.0-only constructs.
- PascalCase file names; composite files prefixed with `Mix`.
- Only `*.yaml` files are discovered by `Provider` — keep docs as `*.md`.
- Reuse the Petstore-style header from `Basic.yaml` unless the situation needs different document structure.

See [`src/DataSets/README.md`](src/DataSets/README.md) for the full authoring checklist.

## Development setup

Fork, then clone the repo:

    git clone git@github.com:your-username/test-data.git

Install dependencies:

    make install

Work on the contribution and check if it passes all QA checks with:

    make

If some of the PHPStan or other checks are to strict or intimidating that is fine, finish what you want to contribute and I'll help you with those, but please make the following command passes. It runs a subset of everything:

    make contrib

You can list all the contrib commands with:

    make help-contrib

Push to your fork and [submit a pull request][pr].

[pr]: https://docs.github.com/en/pull-requests/collaborating-with-pull-requests/proposing-changes-to-your-work-with-pull-requests/creating-a-pull-request
