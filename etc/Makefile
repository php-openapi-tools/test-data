validate-data-sets: ## Validate all OpenAPI data sets in src/DataSets/ ##*AEC*##
	@set -euo pipefail; shopt -s nullglob; for file in src/DataSets/*.{yaml,yml,json}; do \
		echo "Validating $${file}"; \
		$(DOCKER_RUN) vendor/bin/php-openapi validate "$${file}"; \
	done
