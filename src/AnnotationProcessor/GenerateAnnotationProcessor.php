<?php
namespace Hostnet\Component\AccessorGenerator\AnnotationProcessor;

use Hostnet\Component\AccessorGenerator\Annotation\Generate;

/**
 * Process the @Generate annotation and fill in which methods
 * should be generated by the code generator. Store everything
 * in a PropertyInformation object.
 *
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class GenerateAnnotationProcessor implements AnnotationProcessorInterface
{
    /**
     * @see \Hostnet\Component\AccessorGenerator\AnnotationProcessorInterface::processAnnotation()
     * @param object $annotation
     * @param PropertyInformation $information
     */
    public function processAnnotation($annotation, PropertyInformation $info)
    {
        // Only process Generata annotations.
        if ($annotation instanceof Generate) {
            // Only set when not set yet.
            //
            // If the Generate Annotations are processed
            // after the, for example, GeneratedValue
            // annotation, which will disable setter generator
            // do not enable it again.
            $info->willGenerateGet() === null && $info->setGenerateGet($annotation->get && $annotation->is);
            $info->willGenerateSet() === null && $info->setGenerateSet($annotation->set);
            $info->willGenerateAdd() === null && $info->setGenerateAdd($annotation->add && $annotation->set);
            $info->willGenerateRemove() === null && $info->setGenerateRemove($annotation->remove && $annotation->set);
            $info->getType() === null && $annotation->type && $info->setType($annotation->type);

            // Enforce always
            $info->setGenerateStrict($annotation->strict);
            $annotation->type && $info->setTypeHint($annotation->type);
        }
    }

    /**
     * @see AnnotationProcessorInterface::getProcessableAnnotations()
     */
    public function getProcessableAnnotationNamespace()
    {
        return 'Hostnet\Component\AccessorGenerator\Annotation';
    }
}
