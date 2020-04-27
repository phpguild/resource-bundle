<?php

namespace PhpGuild\ResourceBundle\Normalizer;

use PhpGuild\ResourceBundle\Model\Action\ActionInterface;
use PhpGuild\ResourceBundle\Model\Type\Link\LinkTypeInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * Class LinkTypeDenormalizer
 */
class LinkTypeDenormalizer extends AbstractDenormalizer
{
    /**
     * denormalize
     *
     * @param mixed       $data
     * @param string      $type
     * @param string|null $format
     * @param array       $context
     *
     * @return array|object|void
     *
     * @throws ExceptionInterface
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        parent::denormalize($data, $type, $format, $context);

        if (isset($data['label'])) {
            $this->prepareValue($data['label']);
        }

        $this->prepareAction($data);

        if (!isset($data['route'])) {
            return null;
        }

        return $this->normalizer->denormalize($data, $type, $format, $context);
    }

    /**
     * prepareAction
     *
     * @param array $data
     */
    private function prepareAction(array &$data): void
    {
        if (!isset($data['action'])) {
            return;
        }

        $data['route'] = null;
        $action = $this->definitions['actions'][$data['action']] ?? $data['action'];

        if (!is_a($action, ActionInterface::class, true)) {
            return;
        }

        $data['route'] = [
            'name' => str_replace('{_action}', $data['action'], $action::ROUTE_NAME),
            'methods' => $action::ROUTE_METHODS,
            'parameters' => $action::ROUTE_PARAMETERS,
            'requirements' => $action::ROUTE_REQUIREMENTS,
        ];
    }

    /**
     * supportsDenormalization
     *
     * @param mixed       $data
     * @param string      $type
     * @param string|null $format
     * @param array       $context
     *
     * @return bool
     */
    public function supportsDenormalization($data, string $type, string $format = null, array $context = []): bool
    {
        return is_a($type, LinkTypeInterface::class, true);
    }
}
