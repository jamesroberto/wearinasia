<?php
    class Trello_Card_IndexController extends Mage_Core_Controller_Front_Action
    {
        public function getInquiryFormAction(){
            if (!$this->_validateFormKey()) {
                $this->_redirect('');
                return;
            }

            else{
            $params = $this->getRequest()->getParams();
            $name = $params['name'];
            $email = $params['email'];
            $caddress = $params['caddress'];
            $cname = $params['cname'];
            $phone = $params['phone'];
            $product = $params['product'];
            
            //wrap to json
            $encode = json_encode(array(
                "name"=> $name,
                "email"=> $email,
                "company_name"=> $cname,
                "company_address"=> $caddress,
                "phone_number"=> $phone,
                "products"=> $product,
            ), true);
            
            //send email
            $fromEmail  = Mage::getStoreConfig('trans_email/ident_general/email'); // magento general contact email
            $fromName = Mage::getStoreConfig('trans_email/ident_general/name');  // magento general contact name

            $body .= "Nama : ".$name."\n";    
            $body .= "Email : ".$email."\n";
            $body .= "No Handphone : ".$phone."\n";
            $body .= "Nama Perusahaan : ".$cname."\n";
            $body .= "Alamat Perusahaan : ".$caddress."\n";
            $body .= "Produk : ".$product."\n";
                   

            $subject = "Penawaran dari [WIA.ID]: ".$cname; // subject text

            $mail = Mage::getModel('core/email');


            $mail->setBody($body);


            $mail->setFromEmail($fromEmail);
            $mail->setFromName($fromName);

            $mail->setToName('sales');
            $mail->setToEmail('sales@dronestore.id');


            $mail->setSubject($subject);

         try {

            $mail->send();
            //echo $encode;
            $this->pushToTrello($name, $email, $caddress, $phone, $product, $cname);
            $this->sendQuotationConfirmation($name, $email, $caddress, $phone, $product, $cname);
            Mage::getSingleton('core/session')->addSuccess('Thank you for submiting, Your request has been sent and will be respond shortly');

            }
            catch (Exception $e) {
            Mage::getSingleton('core/session')->addError('Unable to send your Request please contact Administrator');
                
            }
        

            
            
            }
        }

        public function pushToTrello($name, $email, $caddress, $phone, $product, $cname){
            $storeId = Mage::app()->getStore()->getStoreId();

            if($storeId == 4){
            $config = json_encode(array(
                "name" => $cname,
                "desc" => "Email: ".$email."\n"."Name: ".$name."\n"."Corporate Address: ".$caddress."\n"."Phone: ".$phone."\n"."Details: ".$product,
                "pos" => "top",
                "idList" => "5be297be7f0bd106f3b9333c",
                "idLabels" => "5bf275ac901e8570c4ec9176",
                
                "keepFromSource" => "all",
                
                "key" => "700d34827dc3666a88acc666bae68806",
                "token" => "92f905e33ca226fc2f89ca2e61c0edbb33fe37395e7e09e858b1981a3f618a34",
                
                    
                
                                    
                                    
                            
            )
    
                );
                    
                    $url    = 'https://api.trello.com/1/cards';
                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_HEADER, false);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_HTTPHEADER,array("Content-type: application/json"));
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $config);
                    
                    $result     = curl_exec($curl);
                    $array = Mage::helper('core')->jsondecode($result);
                    
                    Mage::log($array);
                    
                    
                    
                    curl_close($curl); 
            }

            else{
                $config = json_encode(array(
                    "name" => $cname,
                    "desc" => "Email: ".$email."\n"."Name: ".$name."\n"."Corporate Address: ".$caddress."\n"."Phone: ".$phone."\n"."Details: ".$product,
                    "pos" => "top",
                    "idList" => "5be297be7f0bd106f3b9333c",
                    "idLabels" => "5bf275a417fe48856a3e97ab",
                    
                    "keepFromSource" => "all",
                    
                    "key" => "700d34827dc3666a88acc666bae68806",
                    "token" => "92f905e33ca226fc2f89ca2e61c0edbb33fe37395e7e09e858b1981a3f618a34",
                    
                        
                    
                                        
                                        
                                
                                )
        
                    );
                        
                        $url    = 'https://api.trello.com/1/cards';
                        $curl = curl_init($url);
                        curl_setopt($curl, CURLOPT_HEADER, false);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLOPT_HTTPHEADER,array("Content-type: application/json"));
                        curl_setopt($curl, CURLOPT_POST, true);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $config);
                        
                        $result     = curl_exec($curl);
                        $array = Mage::helper('core')->jsondecode($result);
                        
                        Mage::log($array);
                        
                        
                        
                        curl_close($curl); 

            }
                        
        }

        public function sendQuotationConfirmation($name, $email, $caddress, $phone, $product, $cname)
            {
                $storeId = Mage::app()->getStore()->getStoreId();
                
                
                

                // send email notofication to store owner
                   if($storeId == 4){

                    $fromEmail  = Mage::getStoreConfig('trans_email/ident_general/email'); // magento general contact email
                    $fromName = Mage::getStoreConfig('trans_email/ident_general/name');  // magento general contact name

                    $body .= "Halo Bpk/Ibu,\n\n" .$name."\n\n\n";
                    $body .= "Perkenalkan saya Sinta dari dronestore.id Terimakasih atas permintaan Anda, mohon menunggu permintaan Anda akan segera kami proses dalam waktu paling lama 1 x 24 Jam ( tanggal merah tidak termasuk ) \n";
                    $body .= "Silahkan menghubungi saya pada kontak dibawah ini jika ada yang ingin ditanyakan lebih lanjut \n\n";    
                    $body .= "JL. Scientia Square Selatan\nSummarecon Serpong, Dalton Utara No.050\nTelp : (021) 2222 7825 \nEmail : ".$fromEmail." \n";    



                    $subject = "Konfirmasi Penawaran dari dronestore.id"; // subject text

                    $mail = Mage::getModel('core/email');


                    $mail->setBody($body);


                    $mail->setFromEmail($fromEmail);
                    $mail->setFromName($fromName);

                    $mail->setToName($name);
                    $mail->setToEmail($email);


                    $mail->setSubject($subject);

                    
                    $mail->send();

                        
                        
                       
            
                      

              }
              else{

                $fromEmail  = Mage::getStoreConfig('trans_email/ident_general/email'); // magento general contact email
                $fromName = Mage::getStoreConfig('trans_email/ident_general/name');  // magento general contact name

                $body .= "Halo Bpk/Ibu,\n\n" .$name."\n\n\n";
                $body .= "Perkenalkan saya Sinta dari WIA.ID Terimakasih atas permintaan Anda, mohon menunggu permintaan Anda akan segera kami proses dalam waktu paling lama 1 x 24 Jam ( tanggal merah tidak termasuk ) \n";
                $body .= "Silahkan menghubungi saya pada kontak dibawah ini jika ada yang ingin ditanyakan lebih lanjut \n\n";    
                $body .= "JL. Scientia Square Selatan\nSummarecon Serpong, Dalton Utara No.050\nTelp : (021) 2222 7825 \nEmail : ".$fromEmail." \n";    



                $subject = "Konfirmasi Penawaran dari WIA.ID"; // subject text

                $mail = Mage::getModel('core/email');


                $mail->setBody($body);


                $mail->setFromEmail($fromEmail);
                $mail->setFromName($fromName);

                $mail->setToName($name);
                $mail->setToEmail($email);


                $mail->setSubject($subject);

                

                $mail->send();
                    

          }
            }



        public function getCustomerFormAction(){
            if (!$this->_validateFormKey()) {
                $this->_redirect('');
                return;
            }

            else{
           
            $params = $this->getRequest()->getParams();
            //upload file/image
            $post = $this->getRequest()->getPost();
            try {
                $postObject = new Varien_Object();
                $postObject->setData($post);
                
                            /**************************************************************/
                            $fileName = '';
                            if (isset($_FILES['attachment']['name']) && $_FILES['attachment']['name'] != '') {
                                try {
                                    $fileName       = $_FILES['attachment']['name'];
                                    $fileExt        = strtolower(substr(strrchr($fileName, ".") ,1));
                                    $fileNamewoe    = rtrim($fileName, $fileExt);
                                    $fileName       = preg_replace('/\s+', '', $fileNamewoe) . $Incrementid;
                
                                     $path = Mage::getBaseDir('media') . DS . 'issues';
                                    if(!is_dir($path)){
                                        mkdir($path, 0777, true);
                                    }
                                    $uploader = new Varien_File_Uploader('attachment');
                                    $uploader->setAllowedExtensions(array('jpg', 'png')); //add more file types you want to allow
                                    $uploader->setAllowRenameFiles(false);
                                    $uploader->setFilesDispersion(false);
                                    $uploader->save($path . DS . $fileName );
                                    $ipath = $uploader->getUploadedFileName();
                                } catch (Exception $e) {
                                    Mage::getSingleton('customer/session')->addError($e->getMessage());
                                    $error = true;
                                }
                            }
                            /**************************************************************/
                            
                        } catch(Exception $e){
                            Mage::getSingleton('customer/session')->addError($e->getMessage());
                            $error = true;
                        }
            
            //get data
            $customer = Mage::getSingleton('customer/session')->getCustomer();
            $email = $customer->getEmail();// for email address
            $firstname = $customer->getFirstname();//  For first name
            $lastname = $customer->getLastname();// For last name
            $phone = $params['phone'];
            $title = $params['title'];
            $subjects = $params['subject'];
            $description = $params['description'];
            $urlsource="https://wia.id/media/issues/".$ipath;
            if($ipath !== NULL){
            $encodeIssue = json_encode(array(
                "title" => $title,
                "department" => $subjects,
                "name" => $firstname." ".$lastname,
                "email" => $email,
                "phone" => $phone,
                "description" => $description,
                "image" => $urlsource,

                ),true);
            } else{
                $encodeIssue = json_encode(array(
                    "title" => $title,
                    "department" => $subjects,
                    "name" => $firstname." ".$lastname,
                    "email" => $email,
                    "phone" => $phone,
                    "description" => $description,
                   
    
                    ),true);
            }
            
            $fromEmail  = Mage::getStoreConfig('trans_email/ident_general/email'); // magento general contact email
            $fromName = Mage::getStoreConfig('trans_email/ident_general/name');  // magento general contact name

            $body .= "Nama : ".$firstname." ".$lastname."\n";    
            $body .= "Email : ".$email."\n";
            $body .= "No Handphone : ".$phone."\n";
            $body .= "Judul : ".$title."\n";

            $body .= "Subject : ".$subjects."\n";
            $body .= "Deskripsi : ".$description."\n";
            $body .= "Gambar: ".$ipath."\n";            
        
            $subject = "Customer Issue dari [WIA.ID]" .$subjects; // subject text

            $mail = Mage::getModel('core/email');


            $mail->setBody($body);


            $mail->setFromEmail($fromEmail);
            $mail->setFromName($fromName);

            $mail->setToName('Customer Service');
            $mail->setToEmail('cs@wia.id');


            $mail->setSubject($subject);
        
           try {
           
            //$mail->send();
            Mage::getSingleton('core/session')->addSuccess('Thank you for submiting, Your request has been sent and will be respond shortly');
            echo $encodeIssue;    
            }
            catch (Exception $e) {
            Mage::getSingleton('core/session')->addError('Unable to send your Request please contact Administrator');
                
            
            }

            $this->postToTrello($title, $subjects, $email, $firstname, $lastname, $phone, $description, $ipath);
           
            
            }
        }
        
        public function postToTrello($title, $subjects, $email, $firstname, $lastname, $phone, $description, $ipath){
            
            if($ipath !== NULL){
            $urlsources="media/issues/".$ipath;
            $config = json_encode(array(
                "name" => $title,
                "desc" => "Email: ".$email."\n"."Name: ".$firstname." ".$lastname."\n"."Phone: ".$phone."\n"."Details: ".$description,
                "pos" => "top",
                "idList" => "5be54cec9f80a2899199de42",
                
                "idLabels" => $subjects,
                "keepFromSource" => "all",
                "urlSource" => $urlsources,
                "key" => "700d34827dc3666a88acc666bae68806",
                "token" => "92f905e33ca226fc2f89ca2e61c0edbb33fe37395e7e09e858b1981a3f618a34",
                
                    
                
                                    
                                    
                            
                            )
    
                );
        }
            else{
                $config = json_encode(array(
                    "name" => $title,
                    "desc" => "Email: ".$email."\n"."Name: ".$firstname." ".$lastname."\n"."Phone: ".$phone."\n"."Details: ".$description,
                    "pos" => "top",
                    "idList" => "5be54cec9f80a2899199de42",
                    
                    "idLabels" => $subjects,
                    "keepFromSource" => "all",
                    
                    "key" => "700d34827dc3666a88acc666bae68806",
                    "token" => "92f905e33ca226fc2f89ca2e61c0edbb33fe37395e7e09e858b1981a3f618a34",
                    
                        
                    
                                        
                                        
                                
                                )
        
                    );
            }
            
          
                
                $url    = 'https://api.trello.com/1/cards';
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER,array("Content-type: application/json"));
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $config);
                
                $result     = curl_exec($curl);
                $array = Mage::helper('core')->jsondecode($result);
                var_dump ($array);
                Mage::log($array);
                
                
                
                curl_close($curl); 
                    }
                }

                
        
                
?>