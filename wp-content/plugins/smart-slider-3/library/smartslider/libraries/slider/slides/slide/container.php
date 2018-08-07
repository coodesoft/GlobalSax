<?php

class  N2SSSlideContainer {

    /** @var N2SSSlideComponent[] */
    protected $layers = array();

    /**
     * N2SSSlideContainer constructor.
     *
     * @param N2SmartSliderSlide $slide
     * @param N2SSSlideComponent $component
     * @param Array              $exsistingNodes
     * @param string             $allowedPlacement
     */
    public function __construct($slide, $component, $exsistingNodes, $allowedPlacement) {
        if (is_array($exsistingNodes)) {

            if ($allowedPlacement == 'absolute') {
                $exsistingNodes = array_reverse($exsistingNodes);
            }

            $i = 1;
            foreach ($exsistingNodes AS $node) {
                if (!isset($node['type'])) {
                    $node['type'] = 'layer';
                }
                switch ($node['type']) {
                    case 'content':
                        $this->layers[] = new N2SSSlideComponentContent($i, $slide, $component, $node, $allowedPlacement);
                        break;
                    case 'row':
                        $this->layers[] = new N2SSSlideComponentRow($i, $slide, $component, $node, $allowedPlacement);
                        break;
                    case 'col':
                        $this->layers[] = new N2SSSlideComponentCol($i, $slide, $component, $node, $allowedPlacement);
                        break;
                    case 'layer':
                        try {
                            if (empty($node['item'])) {
                                if (empty($node['items'])) {
                                    continue;
                                }
                                $node['item'] = $node['items'][0];
                            }

                            $layer          = new N2SSSlideComponentLayer($i, $slide, $component, $node, $allowedPlacement);
                            $this->layers[] = $layer;

                        } catch (Exception $e) {
                            var_dump($e->getMessage());
                        }
                        break;
                    case 'group':
                        break;

                }
                $i++;
            }
        }
    }

    public function addContentLayer($slide, $component) {
        $content    = false;
        $layerCount = count($this->layers);
        for ($i = 0; $i < $layerCount; $i++) {
            if ($this->layers[$i] instanceof N2SSSlideComponentContent) {
                $content = $this->layers[$i];
                break;
            }
        }

        if ($content === false) {
            array_unshift($this->layers, new N2SSSlideComponentContent($layerCount + 1, $slide, $component, array(
                'adaptivefont'              => 1,
                'bgimage'                   => '',
                'bgimagex'                  => 50,
                'bgimagey'                  => 50,
                'bgimageparallax'           => 0,
                'bgcolor'                   => '00000000',
                'bgcolorgradient'           => 'off',
                'verticalalign'             => 'center',
                'desktopportraitinneralign' => 'inherit',
                'desktopportraitpadding'    => '10|*|10|*|10|*|10|*|px+',
                'layers'                    => array()
            ), 'absolute'));
        }

        return $content;
    }

    /**
     * @return N2SSSlideComponent[]
     */
    public function getLayers() {
        return $this->layers;
    }

    public function render() {
        $html = '';
        foreach ($this->layers as $layer) {
            $html .= $layer->render();
        }

        return $html;
    }

    public function admin() {
        foreach ($this->layers as $layer) {
            $layer->admin();
        }
    }
}