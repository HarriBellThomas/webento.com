<?php
/**
 * Google Analytics Async Reports Class
 *
 * @package Google Analytics
 * @copyright Incsub 2007-2011 {@link http://incsub.com}
 * @author Andrey Shipilov (Incsub)
 * @license GNU General Public License (Version 2 - GPLv2) {@link http://www.gnu.org/licenses/gpl-2.0.html}
 */

class Google_Analytics_Async_Reports {

    private $_tableId;
    private $_endDate;
    private $_startDate;

    /**
    * public constructor
    */
    public function __construct() {

    }

    /**
    * Sets Profile ID
    *
    * @param string $id (format: 'ga:1234')
    */
    public function setProfile( $id ) {
        //look for a match for the pattern ga:XXXXXXXX, of up to 10 digits
        if ( ! preg_match( '/^ga:\d{1,10}/', $id ) ) {
            throw new Exception( 'Invalid GA Profile ID set. The format should ga:XXXXXX, where XXXXXX is your profile number' );
        }
        $this->_tableId = $id;
        return true;
    }

    /**
    * Set date interval
    */
    public function setDateRange( $startDate, $endDate ) {
        //validate the dates
        if ( ! preg_match( '/\d{4}-\d{2}-\d{2}/', $startDate ) ) {
            throw new Exception( 'Format for start date is wrong, expecting YYYY-MM-DD format' );
        }
        if ( ! preg_match( '/\d{4}-\d{2}-\d{2}/', $endDate ) ) {
            throw new Exception( 'Format for end date is wrong, expecting YYYY-MM-DD format' );
        }
        if ( strtotime( $startDate ) > strtotime( $endDate ) ) {
            throw new Exception( 'Invalid Date Range. Start Date is greated than End Date' );
        }
        $this->_startDate   = $startDate;
        $this->_endDate     = $endDate;
        return true;
    }

    /**
    * Get Report from GA export by $feed_code
    */
    public function getReport( $feed_code = array(), $token ) {
        if ( ! count( $feed_code ) ) {
            die ( 'getReport requires valid parameter to be passed' );
            return false;
        }

        foreach( $feed_code as $key => $value ) {
            $params[] = $key . '=' . $value;
        }
        //URL on GA API
        $GA_Url = 'https://www.google.com/analytics/feeds/data?ids=' . $this->_tableId . '&start-date=' . $this->_startDate . '&end-date=' . $this->_endDate . '&' . implode( '&', $params );

        //call the GA API
        $xml = $this->_call_ga_export_API( $GA_Url, $token );

        //get results
        if ( $xml ) {
            $dom = new DOMDocument();
            $dom->loadXML( $xml );
            $entries = $dom->getElementsByTagName( 'entry' );
            $dims = '';
            foreach ( $entries as $entry ) {
                $dimensions = $entry->getElementsByTagName( 'dimension' );
                foreach ( $dimensions as $dimension ) {
                    $dims .= $dimension->getAttribute( 'value' ) . '~~';
                }

                $metrics = $entry->getElementsByTagName( 'metric' );
                foreach ( $metrics as $metric ) {
                    $name = $metric->getAttribute( 'name' );
                    $mets[$name] = $metric->getAttribute( 'value' );
                }

                $dims = trim( $dims, '~~' );
                $results[$dims] = $mets;

                $dims='';
                $mets='';
            }
        } else {
            throw new Exception( 'getReport() failed to get a valid XML from Google Analytics API service' );
        }
        return $results;
    }

    /**
    * Call request on GA API url
    *
    * @param url
    * @return result
    */
    private function _call_ga_export_API( $url, $token ) {
        return $this->_send_request( $url, array(), array( 'Authorization' => 'GoogleLogin auth=' . $token ) );
    }

    /**
    * Authenticate on Google
    */
    protected function _get_token( $email, $password ) {

        $postdata = array(
            'accountType'   => 'GOOGLE',
            'Email'         => $email,
            'Passwd'        => $password,
            'service'       => 'analytics',
            'source'        => 'ga-async-v01'
        );

        $response = $this->_send_request( 'https://www.google.com/accounts/ClientLogin', $postdata );

        if ( $response) {
            preg_match( '/Auth=(.*)/', $response, $matches );
            if( isset( $matches[1] ) ) {
                return $matches[1];
            }
        }
        return false;
    }

    /**
    * Send POST request
    *
    * @param string $url
    * @param array $params - data for send by 'POST'
    * @return $response
    */

    private function _send_request( $url, $params = array(), $headers = array() ) {

        $args = array(
            'timeout'   => 30,
            'sslverify' => false
        );

        if ( count( $params ) > 0 ) {
            $args['method'] = 'POST';
            $args['body']   = $params;
            $response       = wp_remote_post( $url, $args );
        } else {
            $args['method']             = 'GET';
            $headers['Content-Type']    = 'application/x-www-form-urlencoded';
            $args['headers']            = $headers;
            $response                   = wp_remote_post( $url, $args );
        }

        if( is_wp_error( $response ) ) {
            return false;
        } else {
            if( $response['response']['code'] == 200 ) {
                return $response['body'];
            } elseif ( $response['response']['code'] == 400 ) {
                throw new Exception('Bad request - '.$response);
            } elseif ( $response['response']['code'] == 401 ) {
                throw new Exception('Permission Denied - '.$response);
            } else {
                return false;
            }
        }

    }

}
?>