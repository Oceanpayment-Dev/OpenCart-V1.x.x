<?php
include_once(DIR_APPLICATION."controller/payment/Mobile_Detect.php");
class ControllerPaymentOPUnionpay extends Controller {
	const PUSH 			= "[PUSH]";
	const BrowserReturn = "[Browser Return]";
	const Abnormal 		= "[Abnormal]";
	
	public function confirm()
	{
		$this->load->model('checkout/order');
		
		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('op_unionpay_default_order_status_id'));
	}
	
	protected function index() {
		$detect = new Mobile_Detect(); 
		if($detect->isiOS()){  
			$_SESSION['pages'] = '1';
		}elseif($detect->isMobile()){  
			$_SESSION['pages'] = '1';
		}elseif($detect->isTablet()){ 
			$_SESSION['pages'] = '1'; 
		}else{
			$_SESSION['pages'] = '0';
		}
		
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		$this->load->library('encryption');
		$this->id = 'payment';
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/op_unionpay.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/op_unionpay.tpl';
		} else {
			$this->template = 'default/template/payment/op_unionpay.tpl';
		}	
		
		$this->render();
	}
	
	public function op_unionpay_form()
	{
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/op_unionpay_iframe.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/op_unionpay_iframe.tpl';
		} else {
			$this->template = 'default/template/payment/op_unionpay_iframe.tpl';
		}
	
		$this->children = array(
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
		);

		$this->op_unionpay();
	
		$this->response->setOutput($this->render());

	}
	
	
	public function op_unionpay() {
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');
		$this->load->model('checkout/order');
		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('op_unionpay_default_order_status_id'));
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		//判断是否为空订单
		if (!empty($order_info)) {
			
			$this->load->library('encryption');
			
			$this->load->model('payment/op_unionpay');
			$product_info = $this->model_payment_op_unionpay->getOrderProducts($this->session->data['order_id']);
			
			//获取订单详情
			$productDetails = $this->getProductItems($product_info);
			//获取消费者详情
			$customer_info = $this->model_payment_op_unionpay->getCustomerDetails($order_info['customer_id']);
			
			if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {
				$base_url = $this->config->get('config_url');
			} else {
				$base_url = $this->config->get('config_ssl');
			}
			
			
			
			//提交网关
			$action = $this->config->get('op_unionpay_transaction');
			$this->data['action'] = $action;
			
			//账户号
			$account = $this->config->get('op_unionpay_account');
			$this->data['account'] = $account;

			//订单号
			$order_number = $order_info['order_id'];
			$this->data['order_number'] = $order_number;
			
			//总额
			$order_amount = $this->currency->format($order_info['total'], $order_info['currency_code'], '', FALSE);
			$this->data['order_amount'] = $order_amount;
			
			//币种
			$order_currency = $order_info['currency_code'];
			$this->data['order_currency'] = $order_currency;

			//终端号
			$terminal = $this->config->get('op_unionpay_terminal');
			$this->data['terminal'] = $terminal;
			
			//securecode
			$securecode = $this->config->get('op_unionpay_securecode');
			
			//返回地址
			$backUrl = $base_url.'index.php?route=payment/op_unionpay/callback';
			$this->data['backUrl'] = $backUrl;
			
			//服务器响应地址
			$noticeUrl = $base_url.'index.php?route=payment/op_unionpay/notice';
			$this->data['noticeUrl'] = $noticeUrl;
			
			//备注
			$order_notes = '';
			$this->data['order_notes'] = $order_notes;
			
			//支付方式
			$methods = 'UnionPay';
			$this->data['methods'] = $methods;
			
			//账单人名
			$billing_firstName = $this->OceanHtmlSpecialChars($order_info['payment_firstname']);
			$this->data['billing_firstName'] = $billing_firstName;
			
			//账单人姓
			$billing_lastName = $this->OceanHtmlSpecialChars($order_info['payment_lastname']);
			$this->data['billing_lastName'] = $billing_lastName;
			 
			//账单人邮箱
			$billing_email = $this->OceanHtmlSpecialChars($order_info['email']);
			$this->data['billing_email'] = $billing_email;
			 
			//账单人手机
			$billing_phone = $order_info['telephone'];
			$this->data['billing_phone'] = $billing_phone;
			 
			//账单人国家
			$billing_country = $order_info['payment_country'];
			$this->data['billing_country'] = $billing_country;
			 
			//账单人州
			$billing_state = $order_info['payment_zone'];
			$this->data['billing_state'] = $billing_state;
			 
			//账单人城市
			$billing_city = $order_info['payment_city'];
			$this->data['billing_city'] = $billing_city;
			 
			//账单人地址
			if (!$order_info['payment_address_2']) {
				$billing_address = $order_info['payment_address_1'] ;
			} else {
				$billing_address = $order_info['payment_address_1'] . ',' . $order_info['payment_address_2'];
			}
			$this->data['billing_address'] = $billing_address;
			 
			//账单人邮编
			$billing_zip = empty($order_info['payment_postcode']) ? '999999' : $order_info['payment_postcode'];
			$this->data['billing_zip'] = $billing_zip;
			
			//加密串
			$signValue   = hash("sha256",$account.$terminal.$backUrl.$order_number.$order_currency.$order_amount.$billing_firstName.$billing_lastName.$billing_email.$securecode);
			$this->data['signValue'] = $signValue;
			
			//收货人名
			$ship_firstName = $order_info['shipping_firstname'];
			$this->data['ship_firstName'] = $ship_firstName;
			
			//收货人姓
			$ship_lastName = $order_info['shipping_lastname'];
			$this->data['ship_lastName'] = $ship_lastName;
			
			//收货人手机
			$ship_phone = $order_info['telephone'];
			$this->data['ship_phone'] = $ship_phone;
				
			//收货人国家
			$ship_country = $order_info['shipping_iso_code_2'];
			$this->data['ship_country'] = $ship_country;
				
			//收货人州
			$ship_state = $order_info['shipping_zone'];
			$this->data['ship_state'] = $ship_state;
				
			//收货人城市
			$ship_city = $order_info['shipping_city'];
			$this->data['ship_city'] = $ship_city;
				
			//收货人地址
			if (!$order_info['shipping_address_2']) {
				$ship_addr = $order_info['shipping_address_1'] ;
			} else {
				$ship_addr = $order_info['shipping_address_1'] . ',' . $order_info['shipping_address_2'];
			}
			$this->data['ship_addr'] = $ship_addr;
				
			//收货人邮编
			$ship_zip = empty($order_info['shipping_postcode']) ? '999999' : $order_info['shipping_postcode'];
			$this->data['ship_zip'] = $ship_zip;
			
			//产品名称
			$productName = $productDetails['productName'];
			$this->data['productName'] = $productName;
			
			//产品SKU
			$productSku = $productDetails['productSku'];
			$this->data['productSku'] = $productSku;
			
			//产品数量
			$productNum = $productDetails['productNum'];
			$this->data['productNum'] = $productNum;
			
			//购物车信息
			$cart_info = 'opencart';
			$this->data['cart_info'] = $cart_info;
			
			//API版本
			$cart_api = 'V1.6.2';
			$this->data['cart_api'] = $cart_api;
			
			//支付页面样式
			$pages = isset($_SESSION['pages']) ? $_SESSION['pages'] : 0;
			$this->data['pages'] = $pages;
			
			//附加参数-用户名注册时间
			$ET_REGISTERDATE = empty($customer_info['date_added']) ? 'N/A' : $customer_info['date_added'];
			$this->data['ET_REGISTERDATE'] = $ET_REGISTERDATE;
				
			//附加参数-是否使用优惠券
			$ET_COUPONS = isset($this->session->data['coupon']) ? 'Yes' : 'No';
			$this->data['ET_COUPONS'] = $ET_COUPONS;
			
			
			
			//记录发送到oceanpayment的post log
			$filedate = date('Y-m-d');
			$postdate = date('Y-m-d H:i:s');
			$newfile  = fopen( "oceanpayment_log/" . $filedate . ".log", "a+" );
			$post_log = $postdate."[POST to Oceanpayment]\r\n" .
					"account = "           .$account . "\r\n".
					"terminal = "          .$terminal . "\r\n".
					"backUrl = "           .$backUrl . "\r\n".
					"noticeUrl = "         .$noticeUrl . "\r\n".
					"order_number = "      .$order_number . "\r\n".
					"order_currency = "    .$order_currency . "\r\n".
					"order_amount = "      .$order_amount . "\r\n".
					"billing_firstName = " .$billing_firstName . "\r\n".
					"billing_lastName = "  .$billing_lastName . "\r\n".
					"billing_email = "     .$billing_email . "\r\n".
					"billing_phone = "     .$billing_phone . "\r\n".
					"billing_country = "   .$billing_country . "\r\n".
					"billing_state = "     .$billing_state . "\r\n".
					"billing_city = "      .$billing_city . "\r\n".
					"billing_address = "   .$billing_address . "\r\n".
					"billing_zip = "       .$billing_zip . "\r\n".
					"ship_firstName = "    .$ship_firstName . "\r\n".
					"ship_lastName = "     .$ship_lastName . "\r\n".
					"ship_phone = "        .$ship_phone . "\r\n".
					"ship_country = "  	   .$ship_country . "\r\n".
					"ship_state = "        .$ship_state . "\r\n".
					"ship_city = "         .$ship_city . "\r\n".
					"ship_addr = "  	   .$ship_addr . "\r\n".
					"ship_zip = "          .$ship_zip . "\r\n".
					"methods = "           .$methods . "\r\n".
					"signValue = "         .$signValue . "\r\n".
					"productName = "       .$productName . "\r\n".
					"productSku = "        .$productSku . "\r\n".
					"productNum = "        .$productNum . "\r\n".
					"cart_info = "         .$cart_info . "\r\n".
					"cart_api = "          .$cart_api . "\r\n".
					"order_notes = "       .$order_notes . "\r\n".
					"ET_REGISTERDATE = "   .$ET_REGISTERDATE . "\r\n".
					"ET_COUPONS = "        .$ET_COUPONS . "\r\n";
			$post_log = $post_log . "*************************************\r\n";
			$post_log = $post_log.file_get_contents( "oceanpayment_log/" . $filedate . ".log");
			$filename = fopen( "oceanpayment_log/" . $filedate . ".log", "r+" );
			fwrite($filename,$post_log);
			fclose($filename);
			fclose($newfile);
			
			if ($this->request->get['route'] != 'checkout/guest_step_3') {
				$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
			} else {
				$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
			}
			
			$this->id = 'payment';

			//跳转Redirect
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/op_unionpay_form.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/payment/op_unionpay_form.tpl';
			} else {
				$this->template = 'default/template/payment/op_unionpay_form.tpl';
			}

			
			$this->response->setOutput($this->render());
			
		}else{		
			$this->response->redirect($this->url->link('checkout/cart'));
		}
		

				
		
	}
	
	public function callback() {
			if (isset($this->request->post['order_number']) && !(empty($this->request->post['order_number']))) {
			$this->language->load('payment/op_unionpay');
		
			$this->data['title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

			if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {
				$this->data['base'] = HTTP_SERVER;
			} else {
				$this->data['base'] = HTTPS_SERVER;
			}
		
			$this->data['charset'] = $this->language->get('charset');
			$this->data['language'] = $this->language->get('code');
			$this->data['direction'] = $this->language->get('direction');
			$this->data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));		
			$this->data['text_response'] = $this->language->get('text_response');
			$this->data['text_success'] = $this->language->get('text_success');
			$this->data['text_success_wait'] = sprintf($this->language->get('text_success_wait'), HTTPS_SERVER . 'index.php?route=checkout/success');
            $this->data['text_success_url'] = HTTPS_SERVER . 'index.php?route=checkout/success';
			$this->data['text_failure_url'] = HTTPS_SERVER . 'index.php?route=checkout/checkout';
			$this->data['text_failure'] = $this->language->get('text_failure');		
			$this->data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), HTTPS_SERVER . 'index.php?route=checkout/checkout');
				
			$this->data['text_order_number']='<font color="green">'.$this->request->post['order_number'].'</font>';
			$this->data['text_result']='<font color="green">'.$this->request->post['payment_status'].'</font>';						
			
			
			//返回信息
			$account = $this->config->get('op_unionpay_account');
			$terminal = $this->request->post['terminal'];
			$response_type = $this->request->post['response_type'];
			$payment_id = $this->request->post['payment_id'];
			$order_number = $this->request->post['order_number'];
			$order_currency =$this->request->post['order_currency'];
			$order_amount =$this->request->post['order_amount'];
			$payment_status =$this->request->post['payment_status'];
			$back_signValue = $this->request->post['signValue'];
			$payment_details = $this->request->post['payment_details'];
			$methods = $this->request->post['methods'];
			$payment_country = $this->request->post['payment_country'];
			$order_notes = $this->request->post['order_notes'];
			$card_number = $this->request->post['card_number'];
			$payment_authType = $this->request->post['payment_authType'];
			$payment_risk = $this->request->post['payment_risk'];
			$code_mode = $this->config->get('op_unionpay_code');
			
			
			//用于支付结果页面显示响应代码
			$getErrorCode = explode(':', $payment_details);
			$ErrorCode = $getErrorCode[0];
			$this->data['op_errorCode'] = $ErrorCode;
			$this->data['payment_details'] = $payment_details;
			
			if($code_mode == 1){
				$this->data['actionMsg'] = $this->getActionMessage($ErrorCode);
			}elseif($code_mode == 0){
				$this->data['actionMsg'] = $this->getLocalMessage($ErrorCode);
			}
	
			
			//匹配终端号
			if($terminal == $this->config->get('op_unionpay_terminal')){
				//普通终端号
				$securecode = $this->config->get('op_unionpay_securecode');
			}else{
				$securecode = '';
			}
			
		
			
			//签名数据		
			$local_signValue = hash("sha256",$account.$terminal.$order_number.$order_currency.$order_amount.$order_notes.$card_number.
					$payment_id.$payment_authType.$payment_status.$payment_details.$payment_risk.$securecode);
			
		
			
			//浏览器返回类型
			$this->returnLog(self::BrowserReturn);
			

			//是否来自移动端
			$pages = isset($_SESSION['pages']) ? $_SESSION['pages'] : 0;
			if($pages == 1){
				$MobileType = '(Mobile)';
			}else{
				$MobileType = '';
			}
			
			
			$message = self::BrowserReturn . $MobileType;
			if ($payment_status == 1){           //交易状态
				$message .= 'PAY:Success.';
			}elseif ($payment_status == 0){
				$message .= 'PAY:Failure.';
			}elseif ($payment_status == -1){
				if($payment_authType == 1){
					$message .= 'PAY:Success.';
				}else{
					$message .= 'PAY:Pending.';
				}
			}
			$message .= ' | ' . $payment_id . ' | ' . $order_currency . ':' . $order_amount . ' | ' . $payment_details . "\n";
		
			
			$this->load->model('checkout/order');		
			if (strtoupper($local_signValue) == strtoupper($back_signValue)) {     //数据签名对比

				if($response_type == 0){		
					//正常浏览器跳转
					if(substr($payment_details,0,5) == 20061){	 
						//排除订单号重复(20061)的交易	
						if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/op_unionpay_failure.tpl')) {
							$this->template = $this->config->get('config_template') . '/template/payment/op_unionpay_failure.tpl';
						} else {
							$this->template = 'default/template/payment/op_unionpay_failure.tpl';
						}
						
					}else{
						if ($payment_status == 1 ){  
							//交易成功
							$this->model_checkout_order->update($this->request->post['order_number'], $this->config->get('op_unionpay_success_order_status_id'), $message, FALSE);
							
							unset($this->session->data['coupon']);
							
							$this->data['continue'] = HTTPS_SERVER . 'index.php?route=checkout/success';
							if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/op_unionpay_success.tpl')) {
								$this->template = $this->config->get('config_template') . '/template/payment/op_unionpay_success.tpl';
							} else {
								$this->template = 'default/template/payment/op_unionpay_success.tpl';
							}
								
						}elseif ($payment_status == -1 ){   
							//交易待处理 
							//是否预授权交易
							if($payment_authType == 1){
								$message .= '(Pre-auth)';
								unset($this->session->data['coupon']);
							}
							$this->model_checkout_order->update($this->request->post['order_number'], $this->config->get('op_unionpay_failed_order_status_id'),$message, FALSE);
							
							if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/op_unionpay_failure.tpl')) {
								$this->template = $this->config->get('config_template') . '/template/payment/op_unionpay_failure.tpl';
							} else {
								$this->template = 'default/template/payment/op_unionpay_failure.tpl';
							}
								
						}else{     
							//交易失败
							$this->model_checkout_order->update($this->request->post['order_number'], $this->config->get('op_unionpay_failed_order_status_id'),$message, FALSE);
							
							if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/op_unionpay_failure.tpl')) {
								$this->template = $this->config->get('config_template') . '/template/payment/op_unionpay_failure.tpl';
							} else {
								$this->template = 'default/template/payment/op_unionpay_failure.tpl';
							}
								
						}
 					}								
				}					
			
			}else {     
				//数据签名对比失败
				$this->model_checkout_order->update($this->request->post['order_number'], $this->config->get('op_unionpay_failed_order_status_id'),$message, FALSE);
				
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/op_unionpay_failure.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/payment/op_unionpay_failure.tpl';
				} else {
					$this->template = 'default/template/payment/op_unionpay_failure.tpl';
				}
			}
		}
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		
	}
	
	
	
	
	
	public function notice() {
		//获取推送输入流XML
		$xml_str = file_get_contents("php://input");
		
		//判断返回的输入流是否为xml
		if($this->xml_parser($xml_str)){
			$xml = new DOMDocument();
			$xml->loadXML($xml_str);
		
			//把推送参数赋值到$_REQUEST
			$_REQUEST['response_type']	  = $xml->getElementsByTagName("response_type")->item(0)->nodeValue;
			$_REQUEST['account']		  = $xml->getElementsByTagName("account")->item(0)->nodeValue;
			$_REQUEST['terminal'] 	      = $xml->getElementsByTagName("terminal")->item(0)->nodeValue;
			$_REQUEST['payment_id'] 	  = $xml->getElementsByTagName("payment_id")->item(0)->nodeValue;
			$_REQUEST['order_number']     = $xml->getElementsByTagName("order_number")->item(0)->nodeValue;
			$_REQUEST['order_currency']   = $xml->getElementsByTagName("order_currency")->item(0)->nodeValue;
			$_REQUEST['order_amount']     = $xml->getElementsByTagName("order_amount")->item(0)->nodeValue;
			$_REQUEST['payment_status']   = $xml->getElementsByTagName("payment_status")->item(0)->nodeValue;
			$_REQUEST['payment_details']  = $xml->getElementsByTagName("payment_details")->item(0)->nodeValue;
			$_REQUEST['signValue'] 	      = $xml->getElementsByTagName("signValue")->item(0)->nodeValue;
			$_REQUEST['order_notes']	  = $xml->getElementsByTagName("order_notes")->item(0)->nodeValue;
			$_REQUEST['card_number']	  = $xml->getElementsByTagName("card_number")->item(0)->nodeValue;
			$_REQUEST['methods']	      = $xml->getElementsByTagName("methods")->item(0)->nodeValue;
			$_REQUEST['payment_country']  = $xml->getElementsByTagName("payment_country")->item(0)->nodeValue;
			$_REQUEST['payment_authType'] = $xml->getElementsByTagName("payment_authType")->item(0)->nodeValue;
			$_REQUEST['payment_risk'] 	  = $xml->getElementsByTagName("payment_risk")->item(0)->nodeValue;
		
			$_REQUEST['notice_type'] 	  = $xml->getElementsByTagName("notice_type")->item(0)->nodeValue;
			$_REQUEST['push_dateTime'] 	  = $xml->getElementsByTagName("push_dateTime")->item(0)->nodeValue;
		
				
			//匹配终端号
			if($_REQUEST['terminal'] == $this->config->get('op_unionpay_terminal')){
				//普通终端号
				$securecode = $this->config->get('op_unionpay_securecode');
			}else{
				$securecode = '';
			}
			
			
		}
		
		
		if($_REQUEST['response_type'] == 1){
			
			//交易推送类型
			$this->returnLog(self::PUSH);
			
			//签名数据
			$local_signValue = hash("sha256",$_REQUEST['account'].$_REQUEST['terminal'].$_REQUEST['order_number'].$_REQUEST['order_currency'].$_REQUEST['order_amount'].$_REQUEST['order_notes'].$_REQUEST['card_number'].
					$_REQUEST['payment_id'].$_REQUEST['payment_authType'].$_REQUEST['payment_status'].$_REQUEST['payment_details'].$_REQUEST['payment_risk'].$securecode);
				
			//响应代码
			$getErrorCode	= explode(':', $_REQUEST['payment_details']);
			$errorCode      = $getErrorCode[0];
			
			//数据签名对比
			if (strtoupper($local_signValue) == strtoupper($_REQUEST['signValue'])) {
			
				$this->load->model('checkout/order');
				
				$message = self::PUSH;
				if ($_REQUEST['payment_status'] == 1){           //交易状态
					$message .= 'PAY:Success.';
				}elseif ($_REQUEST['payment_status'] == 0){
					$message .= 'PAY:Failure.';
				}elseif ($_REQUEST['payment_status'] == -1){
					if($_REQUEST['payment_authType'] == 1){
						$message .= 'PAY:Success.';
					}else{
						$message .= 'PAY:Pending.';
					}
				}		
				$message .= ' | ' . $_REQUEST['payment_id'] . ' | ' . $_REQUEST['order_currency'] . ':' . $_REQUEST['order_amount'] . ' | ' . $_REQUEST['payment_details'] . "\n";
				
				
				if($errorCode == 20061){
					//排除订单号重复(20061)的交易
				}else{
					if ($_REQUEST['payment_status'] == 1 ){
						//交易成功
						$this->model_checkout_order->update($_REQUEST['order_number'], $this->config->get('op_unionpay_success_order_status_id'), $message, false);
					}elseif ($_REQUEST['payment_status'] == -1){
						//交易待处理
						//是否预授权交易
						if($_REQUEST['payment_authType'] == 1){
							$message .= '(Pre-auth)';
						}
						$this->model_checkout_order->update($_REQUEST['order_number'], $this->config->get('op_unionpay_pending_order_status_id'), $message, false);
					}else{
						//交易失败
						$this->model_checkout_order->update($_REQUEST['order_number'], $this->config->get('op_unionpay_failed_order_status_id'), $message, false);
					}
				}	
			}
		}
		
		
		if(isset($_REQUEST['notice_type'])){
			
			//异常交易推送类型
			$this->AbnormalLog(self::Abnormal);
			
			//SHA256加密
			$local_signValue = hash("sha256",$_REQUEST['account'].$_REQUEST['terminal'].$_REQUEST['order_number'].$_REQUEST['order_currency'].$_REQUEST['order_amount'].$_REQUEST['order_notes'].$_REQUEST['card_number'].
				$_REQUEST['payment_id'].$_REQUEST['payment_authType'].$_REQUEST['payment_status'].$_REQUEST['payment_details'].$_REQUEST['payment_risk'].$securecode);

			//数据签名对比
			if (strtoupper($local_signValue) == strtoupper($_REQUEST['signValue'])) {
			
				$this->load->model('checkout/order');
			
				$message = '';
				$message .= self::Abnormal;
				$message .= 'payment_id:' . $_REQUEST['payment_id'] . ' | push_dateTime:' . $_REQUEST['push_dateTime'] . "\n";
			
				//获取原本的订单状态
				$order_info = $this->model_checkout_order->getOrder($_REQUEST['order_number']);
				$this->model_checkout_order->update($_REQUEST['order_number'], $order_info['order_status_id'], $message, false);
			}
		}

		
	}

	
	/**
	 * return log
	 */
	public function returnLog($logType){
	
		$filedate   = date('Y-m-d');
		$returndate = date('Y-m-d H:i:s');			
		$newfile    = fopen( "oceanpayment_log/" . $filedate . ".log", "a+" );			
		$return_log = $returndate . $logType . "\r\n".
				"response_type = "       . $_REQUEST['response_type'] . "\r\n".
				"account = "             . $_REQUEST['account'] . "\r\n".
				"terminal = "            . $_REQUEST['terminal'] . "\r\n".
				"payment_id = "          . $_REQUEST['payment_id'] . "\r\n".
				"order_number = "        . $_REQUEST['order_number'] . "\r\n".
				"order_currency = "      . $_REQUEST['order_currency'] . "\r\n".
				"order_amount = "        . $_REQUEST['order_amount'] . "\r\n".
				"payment_status = "      . $_REQUEST['payment_status'] . "\r\n".
				"payment_details = "     . $_REQUEST['payment_details'] . "\r\n".
				"signValue = "           . $_REQUEST['signValue'] . "\r\n".
				"order_notes = "         . $_REQUEST['order_notes'] . "\r\n".
				"card_number = "         . $_REQUEST['card_number'] . "\r\n".
				"methods = "    		 . $_REQUEST['methods'] . "\r\n".
				"payment_country = "     . $_REQUEST['payment_country'] . "\r\n".
				"payment_authType = "    . $_REQUEST['payment_authType'] . "\r\n".
				"payment_risk = "        . $_REQUEST['payment_risk'] . "\r\n";
	
		$return_log = $return_log . "*************************************\r\n";			
		$return_log = $return_log.file_get_contents( "oceanpayment_log/" . $filedate . ".log");			
		$filename   = fopen( "oceanpayment_log/" . $filedate . ".log", "r+" );			
		fwrite($filename,$return_log);	
		fclose($filename);	
		fclose($newfile);
	
	}

	
	/**
	 * Abnormal log
	 */
	public function abnormalLog($logType){
	
		$filedate   = $logType . date('Y-m-d');
		$returndate = date('Y-m-d H:i:s');
		$newfile    = fopen( "oceanpayment_log/" . $filedate . ".log", "a+" );
		$return_log = $returndate . $logType . "\r\n".
				"notice_type = "       	 . $_REQUEST['notice_type'] . "\r\n".
				"account = "             . $_REQUEST['account'] . "\r\n".
				"terminal = "            . $_REQUEST['terminal'] . "\r\n".
				"payment_id = "          . $_REQUEST['payment_id'] . "\r\n".
				"order_number = "        . $_REQUEST['order_number'] . "\r\n".
				"push_dateTime = "       . $_REQUEST['push_dateTime'] . "\r\n".
				"signValue = "        	 . $_REQUEST['signValue'] . "\r\n";
	
		$return_log = $return_log . "*************************************\r\n";
		$return_log = $return_log.file_get_contents( "oceanpayment_log/" . $filedate . ".log");
		$filename   = fopen( "oceanpayment_log/" . $filedate . ".log", "r+" );
		fwrite($filename,$return_log);
		fclose($filename);
		fclose($newfile);
	
	}	
	
	
	
	
	
	/**
	 *  判断是否为xml
	 */
	function xml_parser($str){
		$xml_parser = xml_parser_create();
		if(!xml_parse($xml_parser,$str,true)){
			xml_parser_free($xml_parser);
			return false;
		}else {
			return true;
		}
	}
	
	
	
	/**
	 *  响应代码解决方案
	 */
	public function getActionMessage($ErrorCode)
	{
		//获取线上的响应代码解决方案信息
		$oceanpayment_url = 'http://www.oceanpayment.com.cn/TransResponseCode.php';
			
		$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
			
		$data = array(
				'code' => $ErrorCode,
				'lang' => $lang
		);

			
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_URL,$oceanpayment_url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_TIMEOUT,5);
			
		if (curl_errno($ch)) {
			//超时则获取插件本身
			$op_actionMsg = $this->getLocalMessage($ErrorCode);
		}else{
			$op_actionMsg = curl_exec($ch);
		}
				
	
		return $op_actionMsg;
	}
	
	
	
	
	/**
	 *  获取插件本身的的响应代码解决方案信息
	 *	更新日期2015-04-12
	 */
	public function getLocalMessage($ErrorCode)
	{
		$this->language->load('payment/op_unionpay');
		
		$CodeAction = array(
				'80010' => $this->language->get('text_actionMsg_1'),
				'80011' => $this->language->get('text_actionMsg_1'),
				'80012' => $this->language->get('text_actionMsg_1'),
				'80013' => $this->language->get('text_actionMsg_1'),
				'80014' => $this->language->get('text_actionMsg_2'),
				'80020' => $this->language->get('text_actionMsg_3'),
				'80021' => $this->language->get('text_actionMsg_4'),
				'80022' => $this->language->get('text_actionMsg_5'),
				'80023' => $this->language->get('text_actionMsg_6'),
				'80024' => $this->language->get('text_actionMsg_7'),
				'80025' => $this->language->get('text_actionMsg_1'),
				'80026' => $this->language->get('text_actionMsg_8'),
				'80027' => $this->language->get('text_actionMsg_9'),
				'80028' => $this->language->get('text_actionMsg_10'),
				'80030' => $this->language->get('text_actionMsg_1'),
				'80031' => $this->language->get('text_actionMsg_11'),
				'80032' => $this->language->get('text_actionMsg_12'),
				'80033' => $this->language->get('text_actionMsg_12'),
				'80034' => $this->language->get('text_actionMsg_12'),
				'80035' => $this->language->get('text_actionMsg_12'),
				'80036' => $this->language->get('text_actionMsg_13'),
				'80037' => $this->language->get('text_actionMsg_12'),
				'80050' => $this->language->get('text_actionMsg_14'),
				'80051' => $this->language->get('text_actionMsg_15'),
				'80054' => $this->language->get('text_actionMsg_12'),
				'80061' => $this->language->get('text_actionMsg_12'),
				'80062' => $this->language->get('text_actionMsg_12'),
				'80063' => $this->language->get('text_actionMsg_12'),
				'80064' => $this->language->get('text_actionMsg_12'),
				'80090' => $this->language->get('text_actionMsg_16'),
				'80091' => $this->language->get('text_actionMsg_17'),
				'80092' => $this->language->get('text_actionMsg_18'),
				'80100' => $this->language->get('text_actionMsg_19'),
				'80101' => $this->language->get('text_actionMsg_20'),
				'80120' => $this->language->get('text_actionMsg_21'),
				'80121' => $this->language->get('text_actionMsg_21'),
				'80200' => $this->language->get('text_actionMsg_22'),
		);
	
		
		if(isset($CodeAction[$ErrorCode])){
			$op_actionMsg = $CodeAction[$ErrorCode];
		}else{
			$op_actionMsg = '';
		}
		
		return $op_actionMsg;
	
	}
	
	
	
	/**
	 * 获取订单详情
	 */
	function getProductItems($AllItems){
	
		$productDetails = array();
		$productName = array();
		$productSku = array();
		$productNum = array();
			
		foreach ($AllItems as $item) {
			$productName[] = $item['name'];
			$productSku[] = $item['product_id'];
			$productNum[] = $item['quantity'];
		}
	
		$productDetails['productName'] = implode(';', $productName);
		$productDetails['productSku'] = implode(';', $productSku);
		$productDetails['productNum'] = implode(';', $productNum);
	
		return $productDetails;
	
	}
	
	
	/**
	 * 钱海支付Html特殊字符转义
	 */
	function OceanHtmlSpecialChars($parameter){
	
		//去除前后空格
		$parameter = trim($parameter);
	
		//转义"双引号,<小于号,>大于号,'单引号
		$parameter = str_replace(array("<",">","'","\""),array("&lt;","&gt;","&#039;","&quot;"),$parameter);
	
		return $parameter;
	
	}
	
}
?>
