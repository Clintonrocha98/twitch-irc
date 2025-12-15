.DEFAULT_GOAL := help

.PHONY: help
help: ## Show available commands
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

.PHONY: pint
pint: ## Run Pint code style fixer
	@export XDEBUG_MODE=off
	@$(CURDIR)/vendor/bin/pint --parallel
	@unset XDEBUG_MODE

.PHONY: rector
rector: ## Run Rector
	@$(CURDIR)/vendor/bin/rector process

