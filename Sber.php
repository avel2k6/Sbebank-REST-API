<?php
class Sber
{
  private static $b_username	 	  = 'login';           //Логин Сбербанка для API
  private static $b_pass		  	  = 'password';        //Пароль Сбербанка для API
  private static $b_register		  = 'register_url';    //Метод Сбербанка для регистрации заказа
  private static $b_getstatus	    = 'status_url';      //Метод Сбербанка для получения статуса заказа

	static public function get_url($id_order, $bank_summ, $return_url) // ID Заказа, Сумма заказа, URL возврата клиента
		{
        //Формируеум URL для запроса в банк
				$bank_url       = self::$b_register.'?userName='.self::$b_username.'&password='.self::$b_pass.'&amount='.$bank_summ.'00&currency=643&language=ru&orderNumber='.$id_order.'&returnUrl='.$return_url.'';
				$bank_response 	= file_get_contents($bank_url);
				$b_obj		    	= json_decode($bank_response);
				$target_url 	  = $b_obj->formUrl;
        // Возвращаем путь, по которому надо перенаправить клиента на шлюз Сбербанка
				return $target_url;
		}
	static public function get_status($orderId){

				$bank_response				=  "";
				$bank_check_url 			=  self::$b_getstatus.'?userName='.self::$b_username.'&password='.self::$b_pass.'&orderId='.$orderId.'&language=ru';

				$bank_response 				= file_get_contents($bank_check_url);
				$b_obj					     	=	json_decode($bank_response);
				$bank_OrderStatus  		=	$b_obj->OrderStatus;

				switch ($bank_OrderStatus )
					{
						case 2: 	$card_text = '<span>Платеж принят банком</span>'; break;
						case 4: 	$card_text = '<span>По платежу оформлен возврат средств</span>'; break;
						default:  $card_text=  '<span>Ожидает оплаты</span>'; break;
					}
				$bank['status']	= $bank_OrderStatus;
				$bank['text'] 	= $card_text;

        //Возвращаем массив из ответа банка и расшифровки
				return $bank;
	}

}


?>
