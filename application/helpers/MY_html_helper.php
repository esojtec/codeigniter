<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Script
*
* Generates a script inclusion of a JavaScript file
* Based on the CodeIgniters original Link Tag.
*
* Author(s): Isern Palaus <ipalaus@ipalaus.es>, Viktor Rutberg <wishie@gmail.com>
*
* @access    public
* @param    mixed    javascript sources or an array
* @param    string    language
* @param    string    type
* @param    boolean    should index_page be added to the javascript path
* @return    string
*/    

if ( ! function_exists('script_tag'))
{
    function script_tag($src = '', $language = 'javascript', $type = 'text/javascript', $index_page = FALSE)
    {
        $CI =& get_instance();

        $script = '<script ';

        if(is_array($src))
        {
			$bool = FALSE;
			
			foreach($src as $v)
			{
				if($bool)
					$script .= '<script ';
				else
					$bool = TRUE;
				
				if ( strpos($v, '://') !== FALSE)
				{
					$script .= 'src="'.$v.'.js" ';
				}
				elseif ($index_page === TRUE)
				{
					$script .= 'src="'.$CI->config->site_url($v).'.js" ';
				}
				else
				{
					$script .= 'src="'.JS.$v.'.js" ';
				}
	
				$script .= 'language="'.$language.'" type="'.$type.'"';
	
				$script .= "></script>\n";
			}
        }
        else
        {
            if ( strpos($src, '://') !== FALSE)
            {
                $script .= 'src="'.$src.'.js" ';
            }
            elseif ($index_page === TRUE)
            {
                $script .= 'src="'.$CI->config->site_url($src).'.js" ';
            }
            else
            {
                $script .= 'src="'.JS.$src.'.js" ';
            }

            $script .= 'language="'.$language.'" type="'.$type.'"';

            $script .= "></script>\n";
        }
		
        return $script;
    }
}

// ------------------------------------------------------------------------

/**
 * Link
 *
 * Generates link to a CSS file
 *
 * @access	public
 * @param	mixed	stylesheet hrefs or an array
 * @param	string	rel
 * @param	string	type
 * @param	string	title
 * @param	string	media
 * @param	boolean	should index_page be added to the css path
 * @return	string
 */
if ( ! function_exists('link_tag'))
{
	function link_tag($href = '', $rel = 'stylesheet', $type = 'text/css', $title = '', $media = '', $index_page = FALSE)
	{
		$CI =& get_instance();

		$link = '<link ';

		if (is_array($href))
		{
			$bool = FALSE;
			foreach ($href as $v)
			{
				if($bool)
					$link .= '<link ';
				else
					$bool = TRUE;
					
				if ( strpos($v, '://') !== FALSE)
				{
					$link .= 'href="'.$v.'.css" ';
				}
				elseif ($index_page === TRUE)
				{
					$link .= 'href="'.$CI->config->site_url($v).'.css" ';
				}
				else
				{
					$link .= 'href="'.CSS.$v.'.css" ';
				}
	
				$link .= 'rel="'.$rel.'" type="'.$type.'" ';
	
				if ($media	!= '')
				{
					$link .= 'media="'.$media.'" ';
				}
	
				if ($title	!= '')
				{
					$link .= 'title="'.$title.'" ';
				}
	
				$link .= "/>\n";
			}
		}
		else
		{
			if ( strpos($href, '://') !== FALSE)
			{
				$link .= 'href="'.$href.'.css" ';
			}
			elseif ($index_page === TRUE)
			{
				$link .= 'href="'.$CI->config->site_url($href).'.css" ';
			}
			else
			{
				$link .= 'href="'.CSS.$href.'.css" ';
			}

			$link .= 'rel="'.$rel.'" type="'.$type.'" ';

			if ($media	!= '')
			{
				$link .= 'media="'.$media.'" ';
			}

			if ($title	!= '')
			{
				$link .= 'title="'.$title.'" ';
			}

			$link .= '/>';
		}

		return $link;
	}
}