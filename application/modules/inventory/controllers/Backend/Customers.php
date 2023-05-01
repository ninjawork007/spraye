<?php
//namespace App\Controllers\Backend;
use App\Libraries\DataTables;

class Customers extends MY_Controller {

	// Define create and update rules
	private $rules = [
		'create' => [
			'name' => [
				'rules' => 'min_length[1]|max_length[100]',
				'errors' => [
					"min_length" => "Validation.customers.name_min_length",
					"max_length" => "Validation.customers.name_max_length"
				]
			],
			'internal_name' => [
				'rules' => 'permit_empty|max_length[45]',
				'errors' => [
					"max_length" => "Validation.customers.internal_name_max_length"
				]
			],
			'company_name' => [
				'rules' => 'permit_empty|max_length[100]',
				'errors' => [
					"max_length" => "Validation.customers.company_name_max_length"
				]
			],
			'tax_number' => [
				'rules' => 'permit_empty|max_length[45]',
				'errors' => [
					"max_length" => "Validation.customers.tax_number_max_length"
				]
			],
			'email_address' => [
				'rules' => 'permit_empty|valid_email|max_length[255]',
				'errors' => [
					"valid_email" => "Validation.customers.email_address_invalid",
					"max_length" => "Validation.customers.email_address_max_length"
				]
			],
			'phone_number' => [
				'rules' => 'permit_empty|max_length[20]',
				'errors' => [
					"max_length" => "Validation.customers.phone_number_max_length"
				]
			],
			'address' => [
				'rules' => 'permit_empty|max_length[80]',
				'errors' => [
					"max_length" => "Validation.customers.address_max_length"
				]
			],
			'country' => [
				'rules' => 'permit_empty|max_length[30]',
				'errors' => [
					"max_length" => "Validation.customers.country_max_length"
				]
			],
			'state' => [
				'rules' => 'permit_empty|max_length[30]',
				'errors' => [
					"max_length" => "Validation.customers.state_max_length"
				]
			],
			'zip_code' => [
				'rules' => 'permit_empty|is_natural_no_zero|max_length[12]',
				'errors' => [
					"is_natural_no_zero" => "Validation.customers.zip_code_invalid",
					"max_length" => "Validation.customers.zip_code_max_length"
				]
			],
			'custom_field1' => [
				'rules' => 'permit_empty'
			],
			'custom_field2' => [
				'rules' => 'permit_empty'
			],
			'custom_field3' => [
				'rules' => 'permit_empty'
			],
			'notes' => [
				'rules' => 'permit_empty'
			]
		],

		'update' => [
			'name' => [
				'rules' => 'permit_empty|min_length[1]|max_length[100]',
				'errors' => [
					"max_length" => "Validation.customers.name_max_length"
				]
			],
			'internal_name' => [
				'rules' => 'permit_empty|max_length[45]',
				'errors' => [
					"max_length" => "Validation.customers.internal_name_max_length"
				]
			],
			'company_name' => [
				'rules' => 'permit_empty|max_length[100]',
				'errors' => [
					"max_length" => "Validation.customers.company_name_max_length"
				]
			],
			'tax_number' => [
				'rules' => 'permit_empty|max_length[45]',
				'errors' => [
					"max_length" => "Validation.customers.tax_number_max_length"
				]
			],
			'email_address' => [
				'rules' => 'permit_empty|valid_email|max_length[255]',
				'errors' => [
					"valid_email" => "Validation.customers.email_address_invalid",
					"max_length" => "Validation.customers.email_address_max_length"
				]
			],
			'phone_number' => [
				'rules' => 'permit_empty|max_length[20]',
				'errors' => [
					"max_length" => "Validation.customers.phone_number_max_length"
				]
			],
			'address' => [
				'rules' => 'permit_empty|max_length[80]',
				'errors' => [
					"max_length" => "Validation.customers.address_max_length"
				]
			],
			'country' => [
				'rules' => 'permit_empty|max_length[30]',
				'errors' => [
					"max_length" => "Validation.customers.country_max_length"
				]
			],
			'state' => [
				'rules' => 'permit_empty|max_length[30]',
				'errors' => [
					"max_length" => "Validation.customers.state_max_length"
				]
			],
			'zip_code' => [
				'rules' => 'permit_empty|is_natural_no_zero|max_length[12]',
				'errors' => [
					"is_natural_no_zero" => "Validation.customers.zip_code_invalid",
					"max_length" => "Validation.customers.zip_code_max_length"
				]
			],
			'custom_field1' => [
				'rules' => 'permit_empty'
			],
			'custom_field2' => [
				'rules' => 'permit_empty'
			],
			'custom_field3' => [
				'rules' => 'permit_empty'
			],
			'notes' => [
				'rules' => 'permit_empty'
			]
		]
	];

	public function __construct() {
		parent::__construct();
		$this->rules = (object) $this->rules;
		$this->load->model('Customer_model', 'customer');
		$this->load->model('Invoice_model','INV');
		$this->load->model('../../admin/models/Dashboard_model', 'DashboardModel');
		$this->load->model('../modules/admin/models/payment_invoice_logs_model', 'PartialPaymentModel');
		$this->load->model('../../admin/models/Property_program_job_invoice_model', 'PropertyProgramJobInvoiceModel');
		$this->load->model('../../admin/models/AdminTbl_coupon_model', 'CouponModel');
		$this->load->model('../../admin/models/Invoice_sales_tax_model', 'InvoiceSalesTax');
		$this->load->model('AdminTbl_customer_model', 'CustomerModel');
		$this->load->model('AdminTbl_company_model', 'CompanyModel');
		$this->load->model("Administrator");
		$this->load->model('Refund_invoice_logs_model', 'RefundPaymentModel');
		$this->load->model('AdminTbl_property_model', 'PropertyModel');
	}

	/**
	 * To get all customers
	 * 
	 * Method			GET
	 * Filter			auth
	 * 
	 */
	public function index() {
		$columns = [
			'name',
			'internal_name',
			'company_name',
			'email_address',
			'phone_number',
			'tax_number'
		];
		$datatables = new DataTables($this->request, $columns);

		if($datatables->isRequestValid() === false)
			return $this->failUnauthorized(lang('Errors.unauthorized'));
		
		$draw = $datatables->getDraw();
		$length = $datatables->getLength();
		$start = $datatables->getStart();
		$search = $datatables->getSearchStr();
		$orderBy = $datatables->getOrderBy();
		$orderDir = $datatables->getOrderDir();

		if($orderBy === false || $orderDir === false)
			return $this->fail(lang('Errors.invalid_order'));

		$this->customers->setDtParameters($search, $orderBy, $orderDir, $length, $start);

		return $this->respond(array_merge(
			['draw' => $draw],
			$this->customers->dtGetAllCustomers()
		));
	}

	public function AddBatchCredit(){
		$data = $this->input->post();

		foreach($data["customer_name"] as $RowIndex => $Value){
			$customer_id = $data["customer_name"][$RowIndex];
			$CreditAmount = $data["BatchAmount"][$RowIndex];
			$PaymentType = $data["payment_type"][$RowIndex];

			$PaymentMethodNumber = 1;
			if($PaymentType == "cash"){
				$PaymentMethodNumber = 0;
			}
			if($PaymentType == "other"){
				$PaymentMethodNumber = 3;
			}


			$items = $this->customer->getOneCustomerDetail($customer_id);
			if($items == ""){
				continue;
			}

			if($CreditAmount == "" && $CreditAmount == 0){
				continue;
			}

			$GetPropertyList = $this->customer->getOnecustomerPropert(array("customer_id" => $customer_id));
			$PropertyID = $GetPropertyList->property_id;

			$invoice_data['customer_id'] = $customer_id;
			$invoice_data['cost'] = 0;
			$invoice_data['user_id'] = $this->session->userdata['user_id'];
			$invoice_data['company_id'] = $this->session->userdata['company_id'];
			$invoice_data['program_id'] = -5;
			$invoice_data['property_id'] = $PropertyID;
			$invoice_data['job_id'] = -5;
			$invoice_data['status'] = 0;
			$invoice_data['is_credit'] = 1;
			$invoice_data['is_archived'] = 1;
			$invoice_data['check_number'] = $data["BatchReason"][$RowIndex];
			$invoice_data['payment_method'] = $PaymentMethodNumber;
			$invoice_data['invoice_date'] = $invoice_data['invoice_created'] =  date("Y-m-d H:i:s");
			$invoice_data['is_created'] =  1;
			$invoice_data['credit_given_user'] =  $this->session->userdata['id'];
			$invoice_data['notes'] = $invoice_data['description'] = "Adding {$CreditAmount} Credit to customer's account";

			$invoice_id = $this->INV->createOneInvoice($invoice_data);
			$credit_amount  = $CreditAmount;
			$all_invoice_partials = $this->PartialPaymentModel->getAllPartialPayment(array('invoice_id' => $invoice_id));

			$unpaid = $this->INV->getUnpaidInvoices($customer_id);

			if(!empty($unpaid)){
				foreach ($unpaid as $invoice){
					$invoice_amount  = $invoice->unpaid_amount;
					if($credit_amount >= $invoice_amount && $invoice_amount > 0){
						$inv_details = $this->INV->getOneInvoice($invoice->unpaid_invoice);
						$partial_already_paid = $inv_details->partial_payment;
						$result = $this->INV->createOnePartialPayment(array(
							'invoice_id' => $invoice->unpaid_invoice,
							'payment_amount' => $invoice_amount,
							'payment_applied' => $invoice_amount,
							'payment_datetime' => date("Y-m-d H:i:s"),
							'payment_method' => 5,
							'check_number' => $data["BatchReason"][$RowIndex],
							'cc_number' => null,
							'payment_note' => "Payment made from credit amount {$CreditAmount}",
							'customer_id' => $customer_id,
						));

						$this->INV->updateInvovice(['invoice_id'=> $invoice->unpaid_invoice], ['status' => 2, 'payment_status' => 2, 'last_modify' => date("Y-m-d H:i:s"), 'payment_created' => date("Y-m-d H:i:s"), 'partial_payment' => $partial_already_paid + $invoice_amount, 'opened_date' => date("Y-m-d H:i:s")]);

						$credit_amount -= $invoice_amount;
						$invoice_details = $this->INV->getOneInvoice($invoice->unpaid_invoice);

						if(!isset($invoice_details->sent_date)){
							$this->INV->updateInvovice(['invoice_id'=> $invoice->unpaid_invoice], ['sent_date' => date("Y-m-d H:i:s")]);
						}
					} else if ($credit_amount > 0 && $invoice_amount > 0) {
						$inv_details = $this->INV->getOneInvoice($invoice->unpaid_invoice);
						$partial_already_paid = $inv_details->partial_payment;
						$result = $this->INV->createOnePartialPayment(array(
							'invoice_id' => $invoice->unpaid_invoice,
							'payment_amount' => $credit_amount,
							'payment_applied' => $credit_amount,
							'payment_datetime' => date("Y-m-d H:i:s"),
							'payment_method' => 5,
							'check_number' => $data["BatchReason"][$RowIndex],
							'cc_number' => null,
							'payment_note' => "Payment made from credit amount {$CreditAmount}",
							'customer_id' => $customer_id,
						));

						$this->INV->updateInvovice(['invoice_id'=> $invoice->unpaid_invoice], ['payment_status' => 1, 'last_modify' => date("Y-m-d H:i:s"), 'payment_created' => date("Y-m-d H:i:s"), 'partial_payment' => $partial_already_paid + $credit_amount, 'sent_date' => date("Y-m-d H:i:s")]);
						$credit_amount = 0;
					}
				}

				$this->INV->addCreditPayment($customer_id, $credit_amount, $PaymentType);
				$result = $this->INV->createOnePartialPayment(array(
	                    'invoice_id' => $invoice_id,
	                    'payment_amount' => $CreditAmount,
	                    'payment_applied' => $CreditAmount,
	                    'payment_datetime' => date("Y-m-d H:i:s"),
	                    'payment_method' => 1,
	                    'check_number' => $data["BatchReason"][$RowIndex],
	                    'cc_number' => null,
	                    'payment_note' => "Adding Credit to customer's account",
	                    'customer_id' => $customer_id,
	                    'is_credit_balance' => 1
	                ));
			}else{
				$this->INV->addCreditPayment($customer_id, $credit_amount, $PaymentType);
				$result = $this->INV->createOnePartialPayment(array(
					'invoice_id' => $invoice_id,
					'payment_amount' => $CreditAmount,
					'payment_applied' => $CreditAmount,
					'payment_datetime' => date("Y-m-d H:i:s"),
					'payment_method' => 1,
					'check_number' => $data["BatchReason"][$RowIndex],
					'cc_number' => null,
					'payment_note' => "Adding Credit to customer's account",
					'customer_id' => $customer_id,
					'is_credit_balance' => 1
				));
			}
		}

		$this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000">Credit added to customer</div>');
		redirect("admin/Invoices");
	}

	public function Search(){
		$search = $this->input->get('search', TRUE) ?? '';
		if($search != ""){
			$items = $this->customer->searchCustomerWithNumberName($search);
			$return_array =  array( 'result' => $items);
			echo json_encode($return_array);
		}
	}

	public function DueAmount($id){
		$customer_id = $id;
        // WHERE:
        $whereArr = array(
            'is_archived' => 0,
            'invoice_tbl.customer_id' => $id,
            'invoice_tbl.status !=' => 0
        );

        // WHERE NOT: all of the below true
        $whereArrExclude = array(
            "programs.program_price" => 2,
            "technician_job_assign.is_complete !=" => 1,
            "technician_job_assign.is_complete IS NOT NULL" => null
        );

        // WHERE NOT: all of the below true
        $whereArrExclude2 = array(
            "programs.program_price" => 2,
            "technician_job_assign.invoice_id IS NULL" => null,
            "invoice_tbl.report_id" => 0,
            "property_program_job_invoice2.report_id IS NULL" => null,
        );

        $invoice_total_cost = 0;
        $previous_total = 0;
        $start_date = 0;
        $end_date = 0;

        $whereArrBefore = array(
            'is_archived' => 0,
            'invoice_tbl.customer_id' => $customer_id,
        );

        $data_before_period = $this->INV->ajaxActiveInvoicesTechWithSalesTax($whereArrBefore, "", "", "", "", $whereArrExclude, $whereArrExclude2, "", false);

        // die(print_r($data_before_period));

        if (!empty($data_before_period)) {
            foreach ($data_before_period as $invoice_details) {

                ////////////////////////////////////
                // START INVOICE CALCULATION COST //

                // vars
                $tmp_invoice_id = $invoice_details->invoice_id;

                // cost of all services (with price overrides) - service coupons
                $job_cost_total = 0;
                $where = array(
                    'property_program_job_invoice.invoice_id' => $tmp_invoice_id,
                );
                $proprojobinv = $this->PropertyProgramJobInvoiceModel->getPropertyProgramJobInvoiceCoupon($where);

                if (!empty($proprojobinv)) {
                    foreach ($proprojobinv as $job) {
                        $job_cost = $job['job_cost'];
                        $job_where = array(
                            'job_id' => $job['job_id'],
                            'customer_id' => $job['customer_id'],
                            'property_id' => $job['property_id'],
                            'program_id' => $job['program_id'],
                        );
                        $coupon_job_details = $this->CouponModel->getAllCouponJob($job_where);

                        if (!empty($coupon_job_details)) {
                            foreach ($coupon_job_details as $coupon) {
                                // $nestedData['email'] = json_encode($coupon->coupon_amount);
                                $coupon_job_amm_total = 0;
                                $coupon_job_amm = $coupon->coupon_amount;
                                $coupon_job_calc = $coupon->coupon_amount_calculation;

                                if ($coupon_job_calc == 0) { // flat amm
                                    $coupon_job_amm_total = (float) $coupon_job_amm;
                                } else { // percentage
                                    $coupon_job_amm_total = ((float) $coupon_job_amm / 100) * $job_cost;
                                }

                                $job_cost = $job_cost - $coupon_job_amm_total;

                                if ($job_cost < 0) {
                                    $job_cost = 0;
                                }
                            }
                        }

                        $job_cost_total += $job_cost;
                    }
                    $invoice_total_cost = $job_cost_total;
                    //die(print_r("Inside Conditional: " . $invoice_total_cost));
                } else {
                    $invoice_total_cost = $invoice_details->cost;
                }

                // check price override -- any that are not stored in just that ^^.

                // - invoice coupons
                $coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $tmp_invoice_id));
                foreach ($coupon_invoice_details as $coupon_invoice) {
                    if (!empty($coupon_invoice)) {
                        $coupon_invoice_amm = $coupon_invoice->coupon_amount;
                        $coupon_invoice_amm_calc = $coupon_invoice->coupon_amount_calculation;

                        if ($coupon_invoice_amm_calc == 0) { // flat amm
                            $invoice_total_cost -= (float) $coupon_invoice_amm;
                        } else { // percentage
                            $coupon_invoice_amm = ((float) $coupon_invoice_amm / 100) * $invoice_total_cost;
                            $invoice_total_cost -= $coupon_invoice_amm;
                        }
                        if ($invoice_total_cost < 0) {
                            $invoice_total_cost = 0;
                        }
                    }
                }

                // + tax cost
                $invoice_total_tax = 0;
                $invoice_sales_tax_details = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $tmp_invoice_id));
                if (!empty($invoice_sales_tax_details)) {
                    foreach ($invoice_sales_tax_details as $tax) {
                        if (array_key_exists("tax_value", $tax)) {
                            $tax_amm_to_add = ((float) $tax['tax_value'] / 100) * $invoice_total_cost;
                            $invoice_total_tax += $tax_amm_to_add;
                        }
                    }
                }
                $invoice_total_cost += $invoice_total_tax;
                $total_tax_amount = $invoice_total_tax;

                // END TOTAL INVOICE CALCULATION COST //
                ////////////////////////////////////////

                $total = $invoice_total_cost - $invoice_details->partial_payment;
                $total = number_format($total, 2, '.', '');
                $previous_total += $total;
            }
        }

        $data['invoice_details'] = $this->INV->ajaxActiveInvoicesTechWithSalesTax($whereArr, "", "", "", "", $whereArrExclude, $whereArrExclude2, "", false);

        $credit_arr = array(
            'customer_id' => $customer_id,
            'is_credit_balance' => 1
        );

        $data['credit_details'] = $this->INV->getAllCreditAmountsApplied($credit_arr);

        $data['customer_details'] = $this->CustomerModel->getCustomerDetail($customer_id);
        // die(print_r($data['customer_details']));
        $where_company = array('company_id' => $this->session->userdata['company_id']);
        $data['setting_details'] = $this->CompanyModel->getOneCompany($where_company);

        $where = array('user_id' => $this->session->userdata['user_id']);
        $data['user_details'] = $this->Administrator->getOneAdmin($where);

        // die(print_r($data['invoice_details']));

        $count = 0;
        foreach ($data['invoice_details'] as $index => $inv_deets) {
            $property_details = $this->PropertyModel->getOneProperty(array('property_id'=>$inv_deets->property_id));

            $data['invoice_details'][$index]->property_address = $property_details->property_address;
            $data['invoice_details'][$index]->property_city = $property_details->property_city;
            $data['invoice_details'][$index]->property_state = $property_details->property_state;
            $data['invoice_details'][$index]->property_zip = $property_details->property_zip;
            $data['invoice_details'][$index]->late_fee = $this->INV->getLateFee($inv_deets->invoice_id);
            $data['invoice_details'][$index]->partial_payment = $inv_deets->partial_payment;

            ##### WHERE FOR GETTING ALL PARTIALS AND REFUNDS PAYMENTS FOR INVOICE ID #####
            $where = array(
                'customer_id' => $customer_id,
                'invoice_id' => $inv_deets->invoice_id,
            );

            ##### GETTING ALL PARTIALS FOR INVOICE ID #####
            $inv_deets->partial_payments_logs = $this->PartialPaymentModel->getAllPartialPayment($where);
            ####
            ##### GETTING ALL REFUNDS FOR INVOICE ID #####

            $inv_deets->refund_payments_logs = $this->RefundPaymentModel->getAllPartialRefund($where);
            ####

            ////////////////////////////////////
            // START INVOICE CALCULATION COST //

            // vars
            $tmp_invoice_id = $inv_deets->invoice_id;

            // cost of all services (with price overrides) - service coupons
            $job_cost_total = 0;
            $where = array(
                'property_program_job_invoice.invoice_id' => $tmp_invoice_id,
            );
            $proprojobinv = $this->PropertyProgramJobInvoiceModel->getPropertyProgramJobInvoiceCoupon($where);

            if (!empty($proprojobinv)) {
                foreach ($proprojobinv as $job) {

                    $job_cost = $job['job_cost'];

                    $job_where = array(
                        'job_id' => $job['job_id'],
                        'customer_id' => $job['customer_id'],
                        'property_id' => $job['property_id'],
                        'program_id' => $job['program_id'],
                    );
                    $coupon_job_details = $this->CouponModel->getAllCouponJob($job_where);

                    if (!empty($coupon_job_details)) {

                        foreach ($coupon_job_details as $coupon) {
                            // $nestedData['email'] = json_encode($coupon->coupon_amount);
                            $coupon_job_amm_total = 0;
                            $coupon_job_amm = $coupon->coupon_amount;
                            $coupon_job_calc = $coupon->coupon_amount_calculation;

                            if ($coupon_job_calc == 0) { // flat amm
                                $coupon_job_amm_total = (float) $coupon_job_amm;
                            } else { // percentage
                                $coupon_job_amm_total = ((float) $coupon_job_amm / 100) * $job_cost;
                            }

                            $job_cost = $job_cost - $coupon_job_amm_total;

                            if ($job_cost < 0) {
                                $job_cost = 0;
                            }
                        }
                    }

                    $job_cost_total += $job_cost;
                }
                $invoice_total_cost = (float) $job_cost_total;
            } else {
                $invoice_total_cost = (float) $inv_deets->cost;
            }

            // check price override -- any that are not stored in just that ^^.

            // - invoice coupons
            $coupon_invoice_details = $this->CouponModel->getAllCouponInvoice(array('invoice_id' => $tmp_invoice_id));
            foreach ($coupon_invoice_details as $coupon_invoice) {
                if (!empty($coupon_invoice)) {
                    $coupon_invoice_amm = $coupon_invoice->coupon_amount;
                    $coupon_invoice_amm_calc = $coupon_invoice->coupon_amount_calculation;

                    if ($coupon_invoice_amm_calc == 0) { // flat amm
                        $invoice_total_cost -= (float) $coupon_invoice_amm;
                    } else { // percentage
                        $coupon_invoice_amm = ((float) $coupon_invoice_amm / 100) * $invoice_total_cost;
                        $invoice_total_cost -= $coupon_invoice_amm;
                    }
                    if ($invoice_total_cost < 0) {
                        $invoice_total_cost = 0;
                    }
                }
            }

            // + tax cost
            $invoice_total_tax = 0;
            $invoice_sales_tax_details = $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id' => $tmp_invoice_id));
            if (!empty($invoice_sales_tax_details)) {
                foreach ($invoice_sales_tax_details as $tax) {
                    if (array_key_exists("tax_value", $tax)) {
                        $tax_amm_to_add = ((float) $tax['tax_value'] / 100) * $invoice_total_cost;
                        $invoice_total_tax += $tax_amm_to_add;
                    }
                }
            }
            $invoice_total_cost += $invoice_total_tax;
            $total_tax_amount = $invoice_total_tax;

            // END TOTAL INVOICE CALCULATION COST //
            ////////////////////////////////////////

            $data['invoice_details'][$count]->final_cost = $invoice_total_cost;
            $count += 1;
        }

	    $total_partial = 0;
	    $total_refund_amount = 0;
	    $total_invoice_amount = 0;
	    $total_late_fee = 0;
	    $available_credit = $data["customer_details"]['credit_amount'];
	    if ($invoice_details) {
		     $total_partial_arr =   array_column($data["invoice_details"], 'partial_payment');
		     $total_partial =  array_sum($total_partial_arr);
		     $invoice_id_arr =   array_column($data["invoice_details"], 'invoice_id');
		     $total_cost_arr =   array_column($data["invoice_details"], 'final_cost');
		     $total_late_fee_arr =   array_column($data["invoice_details"], 'late_fee');
		     $total_late_fee = array_sum($total_late_fee_arr);
		     $total_refund_arr =   array_column($data["invoice_details"], 'refund_amount_total'); 
		     $total_refund_amount = array_sum($total_refund_arr);
		     $total_invoice_amount = array_sum($total_cost_arr);
		 }
		 $total_balance_due = $total_invoice_amount - $total_partial + $total_late_fee;
		 echo round($total_balance_due, 2)."";
	}

	/**
	 * To get a single customer by ID
	 * 
	 * Method			GET
	 * Filter			auth
	 */
	public function show($id) {
		$customer = $this->customers->getCustomer($id);

		if(!$customer)
			return $this->failNotFound(lang('Errors.customers.not_found', ['id' => $id]));

		return $this->respond($customer);
	}

	/**
	 * To create a new customer
	 * 
	 * Method			POST
	 * Filter			auth
	 */
	public function create() {
		if(!$this->validateRequestWithRules($this->rules->create))
			return $this->failWithValidationErrors();

		$createFields = [
			'name',
			'internal_name',
			'company_name',
			'tax_number',
			'email_address',
			'phone_number',
			'address',
			'country',
			'state',
			'zip_code',
			'custom_field1',
			'custom_field2',
			'custom_field3',
			'notes'
		];

		$data = $this->buildCreateArray($createFields, true);

		if($data['zip_code'] == '')
			$data['zip_code'] = null;

		if($this->customers->getCustomerByName($data['name']))
			return $this->failResourceExists(lang('Errors.customers.already_exists_name', ['name' => $data['name']]));
		
		if($this->customers->getCustomerByInternalName($data['internal_name']))
			return $this->failResourceExists(lang('Errors.customers.already_exists_internal_name', ['internal_name' => $data['internal_name']]));

		$data['created_by'] = $this->logged_user->id;

		$customer_id = $this->customers->insert($data);
		$new_customer = $this->customers->getCustomer($customer_id);

		return $this->respondCreated($new_customer);
	}

	/**
	 * To edit a customer
	 * 
	 * Method			PUT
	 * Filter			auth:supervisor,admin
	 */
	public function update($id) {
		if(!$this->validateRequestWithRules($this->rules->update))
			return $this->failWithValidationErrors();

		if(!$this->customers->find($id))
			return $this->failNotFound(lang('Errors.customers.not_found', ['id' => $id]));
			
		$updateFields = [
			'name',
			'internal_name',
			'company_name',
			'tax_number',
			'email_address',
			'phone_number',
			'address',
			'country',
			'state',
			'zip_code',
			'custom_field1',
			'custom_field2',
			'custom_field3',
			'notes'
		];

		$data = $this->buildUpdateArray($updateFields, true);

		if($data['zip_code'] == '')
			$data['zip_code'] = null;

		// If trying to edit customer name or internal name, make sure they
		// don't exist already
		if(isset($data['name'])) {
			$duplicateCustomer = $this->customers->getCustomerByName($data['name']);
			if($duplicateCustomer && $duplicateCustomer->id != $id)
				return $this->failResourceExists(lang('Errors.customers.already_exists_name', ['name' => $data['name']]));
		}

		if(isset($data['internal_name'])) {
			$duplicateCustomer = $this->customers->getCustomerByInternalName($data['internal_name']);
			if($duplicateCustomer && $duplicateCustomer->id != $id)
				return $this->failResourceExists(lang('Errors.customers.already_exists_internal_name', ['internal_name' => $data['internal_name']]));
		}

		$this->customers->update($id, $data);

		return $this->respondUpdated($this->customers->getCustomer($id));
	}

	/**
	 * To get latest table -- Table with the 5 most recent customers
	 * No DataTables features will be allowed
	 * 
	 * Method			GET
	 * Filter			auth:supervisor,admin
	 */
	public function show_latest_table() {
		// If user is supervisor, get only records from warehouses that the supervisor has access to
		if($this->logged_user->role == 'supervisor') {
			$warehouseIds = $this->warehouse_relations->getWarehouseIdsByUser($this->logged_user->id);
			$result = $this->customers->dtGetLatest(true, $warehouseIds);
		}else{
			$result = $this->customers->dtGetLatest();
		}

		$draw = $this->request->getVar('draw') ?? false;

		return $this->respond(array_merge(
			['draw' => $draw],
			$result
		));
	}

	/**
	 * To delete a customer
	 * 
	 * Method			DELETE
	 * Filter			auth:admin
	 */
	public function delete($id) {
		if(!$this->customers->find($id))
			return $this->failNotFound(lang('Errors.customers.not_found', ['id' => $id]));
			
		$this->customers->delete($id);

		return $this->respondDeleted([
			'id' => $id
		]);
	}

	/**
	 * To get a list of customers, to be used in a select
	 * 
	 * Method			GET
	 * Filter			auth
	 */
	public function list() {
		return $this->respond($this->customers->getCustomersList());
	}

	/**
	 * To export a CSV file with all customers (admins only)
	 * 
	 * Method			GET
	 * Filter			auth
	 */
	public function export() {
		// Get list of customers, with as much information as we can get
		$customers = $this->customers->getDetailedList();

		// Create a filename and export!
		$filename = date('Y_m_d__H_i_s');
		$filename = "customers__{$filename}.csv";

		helper('csv');

		die(offer_csv_download($customers, $filename));
	}
}