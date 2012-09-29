<?php
require_once (dirname(__FILE__).'/nusoap/nusoap.php');

class EcordiaUserAccount {
    var $apiKey;
    /**
     * @var nusoap_client
     */
	var $client;
	var $results = null;
	var $requestHasBeenExecuted = false;
	var $useSsl = false;
	var $_option_cachedUserInfo = '_ecordia_cachedUserInfo';
	var $_option_cachedUserResults = '_ecordia_cachedUserResults';

	function EcordiaUserAccount($apiKey, $useSsl = false, $live = false) {
		$this->apiKey = $apiKey;
		$this->useSsl = $useSsl;

		if ( $live )
			return $this->init_client();

		$results = get_option( $this->_option_cachedUserResults );
		if ( !empty( $results ) && is_array( $results ) ) {

			$this->results = $results;
			$this->requestHasBeenExecuted = true;
			return;

		}
		// see if we can convert the old object over
		$obj = get_option( $this->_option_cachedUserInfo );
		if ( is_a( $obj, 'EcordiaUserAccount' ) ) {

			update_option( $this->_option_cachedUserResults, $obj->results );
			delete_option( $this->_option_cachedUserInfo );
			$this->results = $obj->results;
			$this->requestHasBeenExecuted = true;

		}

	}

	function init_client() {

		if ( ! is_object( $this->client ) ) {

			$loc = ($this->useSsl ? 'https' : 'http') . '://vesta.ecordia.com/optimizer/v1/usermanagement.svc/' . ($this->useSsl ? 'ssl' : 'nonssl') . '/';
			$this->client = new nusoap_client($loc);
			$this->client->soap_defencoding = 'utf-8';
			$this->client->use_curl = true;

		}
	}

	function UserAccountStatus() {

		$this->init_client();

		$contents = '<GetAccountStatus xmlns="https://vesta.ecordia.com"><submission xmlns:a="http://optimizer.ecordia.com/types/" xmlns:i="http://www.w3.org/2001/XMLSchema-instance"><a:ApiKey>'.$this->apiKey.'</a:ApiKey></submission></GetAccountStatus>';
		$contents = $this->client->serializeEnvelope($contents);
		$endpoint = 'https://vesta.ecordia.com/IUserManagement/GetAccountStatus';

		$results = $this->client->send($contents, $endpoint,0,180);
		$this->results = $results;
		$this->requestHasBeenExecuted = true;

		update_option( $this->_option_cachedUserResults, $results );

	}

	function has_results() {

		return $this->requestHasBeenExecuted;

	}

    function getRawResults() {
        if (!$this->requestHasBeenExecuted) {
            return array();
        } else {
            return $this->results;
        }
    }

    function hasError() {
        return $this->requestHasBeenExecuted && ( ! empty( $this->results['faultcode'] ) || ( method_exists( $this->client, 'getError' ) && $error = $this->client->getError() ) || ! empty( $this->results['GetAccountStatusResult']['Exception']['Message'] ) );
    }

    function getError() {
        if (!$this->requestHasBeenExecuted) {
            return false;
        } elseif (!empty($this->results['faultcode'])) {
            return array('Message'=>$this->results['faultstring']['!'], 'Type'=>$this->results['faultcode']);
        } elseif ($this->client->getError()) {
            return array('Message'=>$this->client->getError(), 'Type'=>1);
        } else {
            return $this->results['GetAccountStatusResult']['Exception'];
        }
    }

    function getErrorMessage() {
        $error = $this->getError();
        if (is_array($error)) {
            return $error['Message'];
        } else {
            return false;
        }
    }

    function getErrorType() {
        $error = $this->getError();
        if (is_array($error)) {
            return $error['Type'];
        } else {
            return false;
        }
    }

    function getAccountStatus() {
        if (!$this->requestHasBeenExecuted) {
            return false;
        } else {
            return $this->results['GetAccountStatusResult']['AccountStatus']['AccountStatus'];
        }
    }

    function getAccountType() {
        if (!$this->requestHasBeenExecuted) {
            return false;
        } else {
            return $this->results['GetAccountStatusResult']['AccountStatus']['AccountType'];
        }
    }

    function getApiKey() {
        return $this->apiKey;
    }

    function getCreditsRemaining() {
        if (!$this->requestHasBeenExecuted) {
            return false;
        } else {
            return $this->results['GetAccountStatusResult']['AccountStatus']['CreditsRemaining'];
        }
    }

    function getCreditsTotal() {
        if (!$this->requestHasBeenExecuted) {
            return false;
        } else {
            return $this->results['GetAccountStatusResult']['AccountStatus']['CreditsTotal'];
        }
    }

    function getKeywordCreditsRemaining() {
        if (!$this->requestHasBeenExecuted) {
            return false;
        } else {
            return $this->results['GetAccountStatusResult']['AccountStatus']['KeywordIdeasCreditsRemaining'];
        }
    }

    function getKeywordCreditsTotal() {
        if (!$this->requestHasBeenExecuted) {
            return false;
        } else {
            return $this->results['GetAccountStatusResult']['AccountStatus']['KeywordIdeasCreditsTotal'];
        }
    }

    function getLastBilledAmount() {
        if (!$this->requestHasBeenExecuted) {
            return false;
        } else {
            return $this->results['GetAccountStatusResult']['AccountStatus']['LastBilledAmount'];
        }
    }

    function getLastBilledDate($format = 'n/j/Y') {
        if (!$this->requestHasBeenExecuted) {
            return false;
        } else {
            return date($format, strtotime(str_replace('T', ' ', $this->results['GetAccountStatusResult']['AccountStatus']['LastBilledDate'])));
        }
    }

    function isInvalidApiKey() {
        if (!$this->requestHasBeenExecuted) {
            return false;
        } else {
            return $this->results['GetAccountStatusResult']['Exception']['Type'] == 'InvalidApiKey';
        }
    }
}
