<?php

class Images
{
    public function __construct($params = null)
    {
        $this->CI =& get_instance();
    }

    public function save_image($image_string, $logo_name = null, $dimensions = array())
    {
        $result = array(
            'bool'=>false
        );

        if($image_string == '') :
            $result['message'][] = 'No image string given';
            return $result;
        endif;

        if(strpos($image_string, 'eval')) :
            $result['message'][] = 'Invalid image';
            $this->CI->regular->respond($result);
            return $result;
        endif;

        if (is_null($logo_name)) {
            $this->CI->load->model('organisations_model');
            $org = $this->CI->organisations_model->read(array(), null, 'zero')[0];

            $logo_name = md5(create_slug($org->account_id.'-'.$org->company_name, '_'));
        }

        $image_path = 'assets/images/' . $logo_name . '.png';
        $save_image = $this->base64_to_image($image_string, $image_path);
		
		
		// get the actual size of the image sent
		// if it is less than the specified size then set the resample width to the original size so it doesnt distort
		list($posted_image_width, $posted_image_height, $posted_image_type, $posted_image_attr) = getimagesize($image_string);
		
		if($posted_image_width < $dimensions['width']){
			$dimensions['width'] = $posted_image_width;
		}

        # resize image -----------------------------------
        if($save_image['bool'])
        {
            if (!empty($dimensions)) {
                $x = null;
                $y = null;
                if (isset($dimensions['width'])) :
                    $x = $dimensions['width'];
                endif;

                if (isset($dimensions['height'])) :
                    $y = $dimensions['height'];
                endif;

                $this->resize_image($image_path, $x, $y);
            }
        }
        /*-----------------------------------------------*/

        return $save_image;
    }

    public function base64_to_image($base64_string, $output_file)
    {
        $result = array(
            'bool'=>false
        );

        $validate_base64 = $this->CI->regular->validate_base64($base64_string);
        $data = explode(',', $base64_string);

        if (isset($data[1]))
        {
            if (!is_dir(FCPATH . 'assets/images/')) {
                mkdir(FCPATH . 'assets/images/');
            }

            $ifp = fopen($output_file, "wb");

            fwrite($ifp, base64_decode($data[1]));
            fclose($ifp);

            $result['bool'] = true;
            $result['message'][] = 'Image saved';
            $result['output_file'] = $output_file;
        }
        else
        {
            $result['message'][] = 'Invalid base64 image string given';
        }

        return $result;
    }

    public function resize_image($image_path, $x = 500, $y = null)
    {
        $config['source_image'] = $image_path;

        # change image width
        if (!is_null($x)) :
            $config['width'] = $x;
        endif;

        # change image height
        if (!is_null($y)) :
            $config['height'] = $y;
        endif;

        # resize image if either x or y values have been given
        if (!is_null($x) || !is_null($y)) {
            if (is_null($x) && is_null($y)) :
                $config['maintain_ratio'] = false;
                $config['master_dim'] = 'width';
            endif;

            $this->CI->load->library('image_lib', $config);

            if (!$this->CI->image_lib->resize()) :
                return false;
            else :
                return true;
            endif;
        }
    }
}