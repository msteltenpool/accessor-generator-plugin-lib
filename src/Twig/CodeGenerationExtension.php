<?php
namespace Hostnet\Component\AccessorGenerator\Twig;

use Doctrine\Common\Inflector\Inflector;

/**
 * Twig extension to have some filters and tags available to be able to write
 * concise template code for the php that we are generating.
 *
 * Filters:
 *   classify:    Turn names with _ into valid PSR-2
 *                Class names. For example: table_name
 *                to TableName.
 *   singularize: Convert plural names to singular ones For example orders to
 *                order or sheep to sheep.
 * Tags:
 *   perline:     This is a block tag to apply prefixes and postfixes to a
 *                multiline twig variable, useful for generating doc blocks,
 *                header boxes or indenting code. It does not generate trailing
 *                spaces on blank lines.
 *
 *                Usage: {% perline %}
 *                       prefix {{lines}} postfix
 *                       {% end perline %}
 *
 * @see Inflector::classify
 * @see Inflector::singularize
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class CodeGenerationExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getTokenParsers()
    {
        return [new PerLineTokenParser()];
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('classify', function ($string) {
                return Inflector::classify($string);
            }),
            new \Twig_SimpleFilter('singularize', function ($string) {
                return Inflector::singularize($string);
            }),
            new \Twig_SimpleFilter('twos_complement_min', function ($int) {
                try {
                    return self::twosComplementMin($int);
                } catch (\DomainException $e) {
                    throw new \Twig_Error_Runtime($e->getMessage(), null, null, $e);
                }
            }),
            new \Twig_SimpleFilter('twos_complement_max', function ($int) {
                try {
                    return self::twosComplementMax($int);
                } catch (\DomainException $e) {
                    throw new \Twig_Error_Runtime($e->getMessage(), null, null, $e);
                }
            }),
            new \Twig_SimpleFilter('decimal_right_shift', function ($input, $amount) {
                try {
                    return self::decimalRightShift($input, $amount);
                } catch (\InvalidArgumentException $e) {
                    throw new \Twig_Error_Runtime($e->getMessage(), null, null, $e);
                }
            })
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Hostnet Twig Code Generation Extension';
    }

    /**
     * @throws \DomainException
     * @param  mixed $bits
     * @return int
     */
    private static function twosComplementMin($bits)
    {
        $bits     = intval($bits);
        $max_bits = PHP_INT_SIZE << 3;

        if ($bits < 1) {
            throw new \DomainException('Bit size must be greater than 0');
        } elseif ($bits > $max_bits) {
            $bits = $max_bits;
        }
        return (-1 << ($bits - 1));
    }

    /**
     * @throws \DomainException
     * @param  mixed $bits
     * @return int
     */
    private static function twosComplementMax($bits)
    {
        $bits     = intval($bits);
        $max_bits = PHP_INT_SIZE << 3;

        if ($bits < 1) {
            throw new \DomainException('Bit size must be greater than 0');
        } elseif ($bits > $max_bits) {
            $bits = $max_bits;
        }
        return (1 << ($bits - 2)) -1 + (1 << ($bits - 2));
    }

    /**
     * @throws \InvalidArgumentException
     * @param  mixed $input
     * @param  int   $amount
     * @return mixed|string
     */
    private static function decimalRightShift($input, $amount = 0)
    {
        // Check input, to see if it is a valid numeric string with a decimal dot and not a
        // decimal comma or any other unwanted chars.
        if (!is_numeric($input) || !preg_match('/[0-9]*\.?[0-9]+/', $input)) {
            throw new \InvalidArgumentException('Input is not a number or numeric string');
        }

        // Check amount to see if it is of integer type.
        if (!is_int($amount)) {
            throw new \InvalidArgumentException('Amount must be an integer value');
        }

        if ($amount > 0) {
            if (($loc = strpos($input, '.')) === false) {
                $loc = strlen($input);
            } else {
                $input = str_replace('.', '', $input);
            }

            $loc -= $amount;
            if ($loc > 0) {
                return substr($input, 0, $loc) . '.' . substr($input, $loc);
            } else {
                return '0.' . str_repeat('0', abs($loc)) . $input;
            }
        } else {
            return $input;
        }
    }
}
