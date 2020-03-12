<?php
/**
 * 
 */
class LockContentController
{
	
	public function __construct()
	{
		$this->load();
		$this->basic = new Basic;
		$this->wpfunc = new WpFunc;
		$this->content_model = new LockContentModel;
	}

	public function load()
	{
		
		add_filter( 'the_content', array( $this, 'content_lock_check' ) );
	}

	public function content_lock_check( $content )
	{

		return $this->content_model->content_lock_check($content);

	}
}