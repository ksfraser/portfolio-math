<?php
declare(strict_types=1);

namespace KSF\Performance\Exceptions;

/**
 * Thrown when there are not enough data points for the requested calculation.
 */
class InsufficientDataException extends CalculationException {}
