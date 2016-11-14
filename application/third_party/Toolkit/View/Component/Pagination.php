<?php
/**
 * Toolkit
 * 
 * Licensed under the Massachusetts Institute of Technology
 * 
 * For full copyright and license information, please see the LICENSE file
 * Redistributions of files must retain the above copyright notice.
 *
 * @author      Lorne Wang < post@lorne.wang >
 * @copyright   Copyright (c) 2014 - 2015 , All rights reserved.
 * @link        http://lorne.wang/projects/toolkit
 * @license     http://lorne.wang/licenses/MIT
 */
namespace Toolkit\View\Component;

/**
 * Pagination
 *
 * @author  Lorne Wang < post@lorne.wang >
 * @package Toolkit\Component
 */
class Pagination
{
    protected $baseURL = ''; // The page we are linking to
    protected $firstURL = ''; // Alternative URL for the First Page.
    protected $prefix = ''; // A custom prefix added to the path.
    protected $suffix = ''; // A custom suffix added to the path.
    protected $totalNumber = 0; // Total number of items (database results)
    protected $perPage = 10; // Max number of items you want shown per page
    protected $numberLinks = 2; // Number of "digit" links to show before/after the+ currently viewed page
    protected $currentPage = 1; // The current page being viewed

    protected $firstLink = 'First';
    protected $firstTagOpen = '&nbsp;';
    protected $firstTagClose = '&nbsp;';

    protected $nextLink = 'Next';
    protected $nextTagOpen = '&nbsp;';
    protected $nextTagClose = '&nbsp;';

    protected $prevLink = 'Prev';
    protected $prevTagOpen = '&nbsp;';
    protected $prevTagClose = '&nbsp;';

    protected $lastLink = 'Last';
    protected $lastTagOpen = '&nbsp;';
    protected $lastTagClose = '&nbsp;';

    protected $numberTagOpen = '&nbsp;';
    protected $numberTagClose = '&nbsp;';

    protected $currentTagOpen = '<strong>';
    protected $currentTagClose = '</strong>';

    protected $fullTagOpen = '';
    protected $fullTagClose = '';

    protected $pageQueryString = false;
    protected $queryStringSegment = 'page';
    protected $displayPages = true;
    protected $attributes = '';
    protected $linkTypes = [];
    protected $reuseQueryString = false;
    protected $dataPageAttr = 'data-pagination-page';

    /**
     * Constructor
     *
     * @param array $params
     */
    public function __construct($params = [])
    {
        $attributes = [];

        if (isset($params['attributes']) && is_array($params['attributes']))
        {
            $attributes = $params['attributes'];
            unset($params['attributes']);
        }

        // Deprecated legacy support for the anchor_class option
        if (isset($params['anchor_class']))
        {
            empty($params['anchor_class']) OR $attributes['class'] = $params['anchor_class'];
            unset($params['anchor_class']);
        }

        $this->parseAttributes($attributes);

        if (count($params) > 0)
        {
            foreach ($params as $key => $val)
            {
                if (isset($this->$key))
                {
                    $this->$key = $val;
                }
            }
        }
    }

    // --------------------------------------------------------------------

    /**
     * 设置总数量
     *
     * @param  integer $totalNumber
     * @return void
     */
    public function setTotalNumber($totalNumber)
    {
        $this->totalNumber = $totalNumber;
    }

    // --------------------------------------------------------------------

    /**
     * 设置每一个数量
     */
    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;
    }

    // --------------------------------------------------------------------

    /**
     * 设置首页链接
     */
    public function setFirstLink($firstLink)
    {
        $this->firstLink = $firstLink;
    }

    // --------------------------------------------------------------------

    /**
     * 设置下一页链接
     */
    public function setNextLink($nextLink)
    {
        $this->nextLink = $nextLink;
    }

    // --------------------------------------------------------------------

    /**
     * 设置下一页开始标签
     */
    public function setNextTagOpen($nextTagOpen)
    {
        $this->nextTagOpen = $nextTagOpen;
    }

    // --------------------------------------------------------------------

    /**
     * 设置下一页闭合标签
     */
    public function setNextTagClose($nextTagClose)
    {
        $this->nextTagClose = $nextTagClose;
    }

    // --------------------------------------------------------------------

    /**
     * 设置上一页链接
     */
    public function setPrevLink($prevLink)
    {
        $this->prevLink = $prevLink;
    }

    // --------------------------------------------------------------------

    /**
     * 设置上一页开始标签
     */
    public function setPrevTagOpen($prevTagOpen)
    {
        $this->prevTagOpen = $prevTagOpen;
    }

    // --------------------------------------------------------------------

    /**
     * 设置上一页闭合标签
     */
    public function setPrevTagClose($prevTagClose)
    {
        $this->prevTagClose = $prevTagClose;
    }

    // --------------------------------------------------------------------

    /**
     * 设置末页链接
     */
    public function setLastLink($lastLink)
    {
        $this->lastLink = $lastLink;
    }

    // --------------------------------------------------------------------

    /**
     * 设置页码位开始标签
     */
    public function setNumberTagOpen($numberTagOpen)
    {
        $this->numberTagOpen = $numberTagOpen;
    }

    // --------------------------------------------------------------------

    /**
     * 设置页码位闭合标签
     */
    public function setNumberTagClose($numberTagClose)
    {
        $this->numberTagClose = $numberTagClose;
    }

    // --------------------------------------------------------------------

    /**
     * 设置当前页码位开始标签
     */
    public function setCurrentTagOpen($currentTagOpen)
    {
        $this->currentTagOpen = $currentTagOpen;
    }

    // --------------------------------------------------------------------

    /**
     * 设置当前页码位闭合标签
     */
    public function setCurrentTagClose($currentTagClose)
    {
        $this->currentTagClose = $currentTagClose;
    }

    // --------------------------------------------------------------------

    /**
     * 设置数字页面半边显示数
     */
    public function setNumberLinks($numberLinks)
    {
        $this->numberLinks = $numberLinks;
    }

    // --------------------------------------------------------------------

    /**
     * Generate the pagination links
     *
     * @return  string
     */
    public function show()
    {
        // If our item count or per-page total is zero there is no need to continue.
        if ($this->totalNumber === 0 OR $this->perPage === 0)
        {
            return '';
        }

        // Calculate the total number of pages
        $numberPages = (int) ceil($this->totalNumber / $this->perPage);

        // Is there only one page? Hm... nothing more to do here then.
        if ($numberPages === 1)
        {
            return '';
        }

        // Set the base page index for starting page number
        $defaultPage = 1;
        $queryGet = $_GET;

        // Determine the current page number.
        if (isset($queryGet[$this->queryStringSegment]) && $queryGet[$this->queryStringSegment] != $defaultPage)
        {

            $this->currentPage = (int) $queryGet[$this->queryStringSegment];
        }

        // Set current page to 1 if it's not valid or if using page numbers instead of offset
        if ( ! is_numeric($this->currentPage) OR $this->currentPage === 0)
        {
            $this->currentPage = $defaultPage;
        }

        $this->numberLinks = (int) $this->numberLinks;

        if ($this->numberLinks < 1)
        {
            throw new \Exception('Your number of links must be a positive number.');
        }

        // Is the page number beyond the result range?
        // If so we show the last page

        if ($this->currentPage > $numberPages)
        {
            $this->currentPage = $numberPages;
        }

        $uriPageNumber = $this->currentPage;

        // Calculate the start and end numbers. These determine
        // which number to start and end the digit links with
        $start = (($this->currentPage - $this->numberLinks) > 0) ? $this->currentPage - ($this->numberLinks - 1) : 1;
        $end = (($this->currentPage + $this->numberLinks) < $numberPages) ? $this->currentPage + $this->numberLinks : $numberPages;

        // Is pagination being used over GET or POST? If get, add a per_page query
        // string. If post, add a trailing slash to the base URL if needed

        //print_r($this->baseURL);die;

        // 处理根链接
        $this->baseURL = trim($this->baseURL);
        unset($queryGet[$this->queryStringSegment]);

        $queryMark = substr($this->baseURL, -1) == '?' ? '' : '?';
        $queryString = http_build_query($queryGet);

        $buildQuery = count($queryGet) > 0 ? "{$queryMark}{$queryString}&amp;" : $queryMark;
        $this->baseURL = rtrim($this->baseURL) . $buildQuery . $this->queryStringSegment . '=';


        // And here we go...
        $output = '';

        // Render the "First" link
        if ($this->firstLink !== false && $this->currentPage > ($this->numberLinks + 1))
        {
            $firstURL = ($this->firstURL === '') ? $this->baseURL : $this->firstURL;

            // Take the general parameters, and squeeze this pagination-page attr in there for JS fw's
            $attributes = sprintf('%s %s="%d"', $this->attributes, $this->dataPageAttr, 1);

            $output .= $this->firstTagOpen . '<a href="' . $firstURL . '"' . $attributes . $this->_attr_rel('start') . '>'
                . $this->firstLink . '</a>' . $this->firstTagClose;
        }

        // Render the "previous" link
        if ($this->prevLink !== false && $this->currentPage !== 1)
        {
            $i = $uriPageNumber - 1;

            // Take the general parameters, and squeeze this pagination-page attr in there for JS fw's
            $attributes = sprintf('%s %s="%d"', $this->attributes, $this->dataPageAttr, (int) $i);

            if ($i === $defaultPage && $this->firstURL !== '')
            {
                $output .= $this->prevTagOpen . '<a href="' . $this->firstURL . '"' . $attributes . $this->_attr_rel('prev') . '>'
                    . $this->prevLink . '</a>' . $this->prevTagClose;
            }
            else
            {
                $append = ($i === $defaultPage) ? $queryString : $this->prefix . $i . $this->suffix;
                $output .= $this->prevTagOpen . '<a href="' . $this->baseURL . $append . '"' . $attributes . $this->_attr_rel('prev') . '>'
                    . $this->prevLink . '</a>' . $this->prevTagClose;
            }

        }

        // Render the pages
        if ($this->displayPages !== false)
        {
            // Write the digit links
            for ($loop = $start - 1; $loop <= $end; $loop++)
            {
                // Take the general parameters, and squeeze this pagination-page attr in there for JS fw's
                $attributes = sprintf('%s %s="%d"', $this->attributes, $this->dataPageAttr, (int) $loop);

                if ($loop >= $defaultPage)
                {
                    if ($this->currentPage === $loop)
                    {
                        $output .= $this->currentTagOpen . $loop . $this->currentTagClose; // Current page
                    }
                    else
                    {
                        $n = ($loop === $defaultPage) ? '' : $loop;

                        if ($n === '' && ! empty($this->firstURL))
                        {
                            $output .= $this->numberTagOpen . '<a href="' . $this->firstURL . '"' . $attributes . $this->_attr_rel('start') . '>'
                                . $loop . '</a>' . $this->numberTagClose;
                        }
                        else
                        {
                            $append = ($n === '') ? $defaultPage : $this->prefix . $n . $this->suffix;
                            $output .= $this->numberTagOpen . '<a href="' . $this->baseURL . $append . '"' . $attributes . $this->_attr_rel('start') . '>'
                                . $loop . '</a>' . $this->numberTagClose;
                        }
                    }
                }
            }
        }

        // Render the "next" link
        if ($this->nextLink !== false && $this->currentPage < $numberPages)
        {
            $i = $this->currentPage + 1;

            // Take the general parameters, and squeeze this pagination-page attr in there for JS fw's
            $attributes = sprintf('%s %s="%d"', $this->attributes, $this->dataPageAttr, (int) $i);

            $output .= $this->nextTagOpen . '<a href="' . $this->baseURL . $this->prefix . $i . $this->suffix . '"' . $attributes
                . $this->_attr_rel('next') . '>' . $this->nextLink . '</a>' . $this->nextTagClose;
        }

        // Render the "Last" link
        if ($this->lastLink !== false && ($this->currentPage + $this->numberLinks) < $numberPages)
        {
            // Take the general parameters, and squeeze this pagination-page attr in there for JS fw's
            $attributes = sprintf('%s %s="%d"', $this->attributes, $this->dataPageAttr, (int) $numberPages);

            $output .= $this->lastTagOpen . '<a href="' . $this->baseURL . $this->prefix . $numberPages . $this->suffix . '"' . $attributes . '>'
                . $this->lastLink . '</a>' . $this->lastTagClose;
        }

        // Kill double slashes. Note: Sometimes we can end up with a double slash
        // in the penultimate link so we'll kill all double slashes.
        $output = preg_replace('#([^:])//+#', '\\1/', $output);

        // Add the wrapper HTML if exists
        return $this->fullTagOpen . $output . $this->fullTagClose;
    }

    // --------------------------------------------------------------------

    /**
     * Parse attributes
     *
     * @param   array
     * @return  void
     */
    protected function parseAttributes($attributes)
    {
        isset($attributes['rel']) OR $attributes['rel'] = true;
        $this->linkTypes = ($attributes['rel'])
            ? ['start' => 'start', 'prev' => 'prev', 'next' => 'next']
            : [];
        unset($attributes['rel']);

        $this->attributes = '';
        foreach ($attributes as $key => $value)
        {
            $this->attributes .= ' ' . $key . '="' . $value . '"';
        }
    }

    // --------------------------------------------------------------------

    /**
     * Add "rel" attribute
     *
     * @link    http://www.w3.org/TR/html5/links.html#linkTypes
     * @param   string
     * @return  string
     */
    protected function _attr_rel($type)
    {
        if (isset($this->linkTypes[$type]))
        {
            unset($this->linkTypes[$type]);

            return ' rel="' . $type . '"';
        }

        return '';
    }

}