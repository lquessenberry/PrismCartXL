#!/bin/bash

echo "Generating demo content..."

echo "Creating demo products..."
ddev drush devel-generate:content 50 --type=commerce_product

echo "Creating demo customers..."
ddev drush devel-generate:users 10 --roles="authenticated"

echo "Creating demo orders..."
ddev drush devel-generate:content 20 --type=commerce_order

echo "Demo content generation complete!"
