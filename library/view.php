<?php
/**
 * BirSaat Framework: View container and renderer
 *
 * A BirSaat view must be built by a controller, and is generally rendered
 * as the last action of the dispatcher.
 *
 * @author Imran Nazar <tf@imrannazar.com>
 */

class bsView
{
    private $__values = array();
    private $__info = array();
    private $__assets;

    /**
     * View constructor
     * @throws bsException if the caller is not a controller
     */
    public function __construct()
    {
        $bt = debug_backtrace();
        $caller = new ReflectionObject($bt[1]['object']);
        $controller = $caller->getName();

        if (!preg_match('#^(\w+)Controller$#i', $controller, $cname)) {
            throw new bsException('View initialised from an unknown controller: '.$controller);
        }

        $this->__info = array(
            'config'    => bsFactory::get('config'),
            'folder'    => $cname[1],
            'file'      => '',
            'title'     => bsFactory::get('config')->app_name,
            'template'  => '',
            'formatter' => '',
            'ajax'      => isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'
        );

        $this->__assets = array('css' => array(), 'js' => array());
    }

    /**
     * Get a view value
     * @param key string Name of the view value to get
     * @return mixed Value associated with the key
     */
    public function __get($key)
    {
        return isset($this->__values[$key]) ? $this->__values[$key] : null;
    }

    /**
     * Set a view value
     * @param key string Name of the view value to set
     * @param val mixed Associated value
     */
    public function __set($key, $val)
    {
        $this->__values[$key] = $val;
    }

    /**
     * Retrieve information about the current view
     * @return array Details of the view
     */
    public function get_info()
    {
        return $this->__info;
    }

    /**
     * Set the name of the view file. This is usually called by dispatch.
     * @param file string Name of the view file to use for rendering
     */
    public function set_file($file)
    {
        $this->__info['file'] = $file;
    }

    /**
     * Set the formatter to use for output
     * @param formatter string One of "json", "html"
     * @throws bsException if an invalid formatter is given
     */
    public function set_formatter($formatter)
    {
        if (!in_array($formatter, array('json', 'html'))) {
            throw new bsException('Unsupported formatter: '.$formatter);
        }
        $this->__info['formatter'] = $formatter;
    }

    /**
     * Set the template file to use for page layout
     * @param template string Name of the template to use
     */
    public function set_template($template)
    {
        $this->__info['template'] = $template;
    }

    /**
     * Attach additional CSS or JS assets to the page template
     * @param type string One of "css", "js"
     * @param file string Full filename of the asset to load
     * @throws bsException if an invalid asset type is given
     */
    public function add_asset($type, $file)
    {
        if (!in_array($type, array('css', 'js'))) {
            throw new bsException('Attempt to add an unknown asset type: '.$file);
        }
        $this->__assets[$type][] = $file;
    }

    /**
     * Render the view. If requested over AJAX, the template does not exist,
     * or JSON format is requested, a template is not output.
     * @throws bsException if the view file does not exist
     */
    public function render()
    {
        if (!$this->__info['template']) {
            $this->__info['template'] = 'index';
        }
        
        $this->__info['folder'] = strtolower(basename($this->__info['folder']));
        $this->__info['file'] = strtolower(basename($this->__info['file']));
        $this->__info['template'] = strtolower(basename($this->__info['template']));

        switch ($this->__info['formatter']) {
            case 'json':
                header('Content-type: application/json');
                echo $this->render_view('json');
                break;

            default:
                $view_output = $this->render_view();
                $template_file = sprintf('../templates/%s.php', $this->__info['template']);
                if ($this->__info['ajax'] || !file_exists($template_file)) {
                    echo $view_output;
                } else {
                    ob_start();
                    include $template_file;
                    echo ob_get_clean();
                }
                break;
        }
    }

    private function render_view($format = null)
    {
        if ($format) {
            $view_file = sprintf('../views/%s/%s.%s.php', $this->__info['folder'], $this->__info['file'], $format);
        } else {
            $view_file = sprintf('../views/%s/%s.php', $this->__info['folder'], $this->__info['file']);
        }

        if (!file_exists($view_file)) {
            throw new bsException('View does not exist: '.$this->__info['folder'].'/'.$this->__info['file']);
        }

        ob_start();
        include $view_file;
        return ob_get_clean();
    }
}
