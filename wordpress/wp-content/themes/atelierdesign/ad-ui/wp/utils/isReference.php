<?php

/**
 * Checks if a string is a token reference (a.k.a. a string wrapped in {curly.braces})
 *
 * @param mixed $value
 * @return bool
 */
function isReference($value)
{
  if (!is_string($value)) return false;

  if (substr($value, 0, 1) === '{' && substr($value, -1) === '}') {
    return true;
  }

  if ((substr($value, 0, 2) === '"{' && substr($value, -2) === '}"') || (substr($value, 0, 2) === "'{" && substr($value, -2) === "'}")) {
    return true;
  }

  return false;
}

function isTailwindReference($value)
{
  if (!is_string($value)) return false;

  if (str_starts_with($value, '{tailwindcss.') && str_ends_with($value, '}')) {
    return true;
  }

  if ((str_starts_with($value, '"{tailwindcss.') && str_ends_with($value, '}"')) || (str_starts_with($value, "'{tailwindcss.") && str_ends_with($value, "'}"))) {
    return true;
  }

  return false;
}

function isConfigReference($value)
{
  if (!is_string($value)) return false;

  if (str_starts_with($value, '{config.') && str_ends_with($value, '}')) {
    return true;
  }

  if ((str_starts_with($value, '"{config.') && str_ends_with($value, '}"')) || (str_starts_with($value, "'{config.") && str_ends_with($value, "'}"))) {
    return true;
  }

  return false;
}

function isCalcReference($value)
{
  if (!is_string($value)) return false;

  if (str_starts_with($value, '{calc.') && str_ends_with($value, '}')) {
    return true;
  }

  if ((str_starts_with($value, '"{calc.') && str_ends_with($value, '}"')) || (str_starts_with($value, "'{calc.") && str_ends_with($value, "'}"))) {
    return true;
  }

  return false;
}

