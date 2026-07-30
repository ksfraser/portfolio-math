<?php
declare(strict_types=1);

namespace KSF\Performance\Exceptions;

/**
 * Thrown when a numerical root-finding algorithm fails to converge.
 */
class ConvergenceException extends CalculationException {}
