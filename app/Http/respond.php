<?php namespace App\Http;

use Illuminate\Http\Response;
use Lukasoppermann\Httpstatus\Httpstatus;

class Respond{
  // response
  protected $response;
  protected $request;
  protected $httpstatus;
  protected $status;
  // make sure to include / at the end
  protected $devUrl = "http://dev.formandsystem.com/";

  function __construct(Response $response, Httpstatus $httpstatus)
  {
    $this->response = $response;
    $this->httpstatus = $httpstatus;
  }

    /*
     * set devUrl
     */
    public function setUrl($url)
    {
      $this->devUrl = $url;
    }

    /*
     * get devUrl
     */
    public function getUrl()
    {
      return $this->devUrl;
    }

    /*
     * get status
     *
     * @return int
     */
    public function getStatus()
    {
      return $this->status;
    }

    /*
     * set status
     *
     * @return void
     */
    public function setStatus($status)
    {
      $this->status = $status;
    }

    /*
     * add header to response
     *
     * @param string $type
     * @param string $header
     *
     * @return void
     */
    public function addHeader( $type, $header )
    {
      $this->response->header($type, $header);
    }

    /*
     * return url to error docs
     *
     * @method errorUrl
     *
     * @param int $error_code
     *
     * @return string
     */
    private function errorUrl( $errorCode )
    {
      return $this->getUrl().'errors/#'.$errorCode;
    }

    /*
     * return info url
     *
     * @method infoUrl
     *
     * @param string $handle
     *
     * @return string
     */
    private function infoUrl( $handle )
    {
      return $this->getUrl().$handle;
    }

    /*
     * return a response
     *
     * @method respond
     *
     * @param array $data
     *
     * @return Illuminate\Http\Response
     */
    public function respond($data)
    {
      $this->response->setContent($data);
      $this->response->setStatusCode($this->getStatus());

      return $this->response;
    }

    /*
     * respond with error
     *
     * @method error
     *
     * @param array $data
     *
     * @return Illuminate\Http\Response
     */
    public function error($data = [])
    {
      $error = array_merge(
        [
          'status' => $this->getStatus(),
          'title' => $this->httpstatus->text($this->getStatus())
        ],
        $data
      );

      if( array_key_exists('code', $error) && !is_null($error['code']) && is_int($error['code']) )
      {
        $error['links'] = [
          'about' => $this->errorUrl($error['code'])
        ];
      }

      return $this->respond(
        ['errors' =>
          [
            'error' => $error
          ]
        ]);
    }
    /*
     * respond with badRequest
     *
     * @param array $data
     *
     * @return Illuminate\Http\Response
     */
    public function badRequest($data)
    {
      $this->setStatus(400);
      return $this->error($data);
    }
    /*
     * respond with AuthenticationFailed
     *
     * @param array $data
     *
     * @return Illuminate\Http\Response
     */
    public function authenticationFailed($data)
    {
      $this->setStatus(401);
      return $this->error($data);
    }
    /*
     * respond with forbidden
     *
     * @param array $data
     *
     * @return Illuminate\Http\Response
     */
    public function forbidden($data)
    {
      $this->setStatus(403);
      return $this->error($data);
    }
    /*
     * respond with notFound
     *
     * @param array $data
     *
     * @return Illuminate\Http\Response
     */
    public function notFound($data)
    {
      $this->setStatus(404);
      return $this->error($data);
    }
    /*
     * respond with NotAcceptable
     *
     * @param array $data
     *
     * @return Illuminate\Http\Response
     */
    public function NotAcceptable($data)
    {
      $this->setStatus(406);
      return $this->error($data);
    }
    /*
     * respond with NotAcceptable
     *
     * @param array $data
     *
     * @return Illuminate\Http\Response
     */
    public function UnsupportedMediaType($data)
    {
      $this->setStatus(415);
      return $this->error($data);
    }
    /*
     * respond with internal
     *
     * @param array $data
     *
     * @return Illuminate\Http\Response
     */
    public function internal($data = [])
    {
      $this->setStatus(500);
      return $this->error($data);
    }
    /*
     * respond with Data
     *
     * @method withData
     *
     * @param array $data
     *
     * @return Illuminate\Http\Response
     */
    public function withData($data)
    {
      $data = array_merge([
          "jsonapi" => ["version" => "1.0"]
        ], $data
      );

      return $this->respond($data);
    }

    /*
     * respond ok
     *
     * @param array $data
     *
     * @return Illuminate\Http\Response
     */
    public function ok($data)
    {
      $this->setStatus(200);

      return $this->withData($data);
    }
    /*
     * respond created
     *
     * @return Illuminate\Http\Response
     */
    public function created($data, $location)
    {
      $this->setStatus(201);
      $this->addHeader('Location', $location);

      return $this->withData($data, $this->getStatus());
    }

    /*
     * respond noContent
     *
     * @return Illuminate\Http\Response
     */
    public function noContent()
    {
      $this->setStatus(204);

      return $this->respond(null, $this->getStatus());
    }

}
