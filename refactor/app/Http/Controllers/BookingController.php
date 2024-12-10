<?php

namespace DTApi\Http\Controllers;

use DTApi\Services\JobService;
use DTApi\Services\DistanceService;
use Illuminate\Http\Request;

/**
 * Class BookingController
 * @package DTApi\Http\Controllers
 */
class BookingController extends Controller
{
    protected $jobService;
    protected $distanceService;

    /**
     * BookingController constructor.
     *
     * @param JobService $jobService
     * @param DistanceService $distanceService
     */
    public function __construct(JobService $jobService, DistanceService $distanceService)
    {
        $this->jobService = $jobService;
        $this->distanceService = $distanceService;
    }

    /**
     * Get all jobs based on user type.
     */
    public function index(Request $request)
    {
        $userId = $request->get('user_id');
        $userType = $request->__authenticatedUser->user_type;

        $jobs = $this->jobService->getJobs($userId, $userType, $request);

        return response()->json($jobs);
    }

    /**
     * Get specific job details.
     */
    public function show($id)
    {
        $jobDetails = $this->jobService->getJobDetails($id);

        return response()->json($jobDetails);
    }

    /**
     * Create a new job.
     */
    public function store(Request $request)
    {
        $user = $request->__authenticatedUser;
        $jobData = $request->all();

        $job = $this->jobService->createJob($user, $jobData);

        return response()->json($job);
    }

    /**
     * Update an existing job.
     */
    public function update(Request $request, $id)
    {
        $user = $request->__authenticatedUser;
        $jobData = $request->all();

        $updatedJob = $this->jobService->updateJob($id, $jobData, $user);

        return response()->json($updatedJob);
    }

    /**
     * End a job.
     */
    public function endJob(Request $request)
    {
        $jobData = $request->all();

        $response = $this->jobService->endJob($jobData);

        return response()->json($response);
    }

    /**
     * Accept a job.
     */
    public function acceptJob(Request $request)
    {
        $user = $request->__authenticatedUser;
        $jobData = $request->all();

        $response = $this->jobService->acceptJob($jobData, $user);

        return response()->json($response);
    }

    /**
     * Accept a job by ID.
     */
    public function acceptJobWithId(Request $request, $jobId)
    {
        $user = $request->__authenticatedUser;

        $response = $this->jobService->acceptJobById($jobId, $user);

        return response()->json($response);
    }

    /**
     * Cancel a job.
     */
    public function cancelJob(Request $request)
    {
        $user = $request->__authenticatedUser;
        $jobData = $request->all();

        $response = $this->jobService->cancelJob($jobData, $user);

        return response()->json($response);
    }

    /**
     * Reopen a job.
     */
    public function reopen(Request $request)
    {
        $jobData = $request->all();

        $response = $this->jobService->reopenJob($jobData);

        return response()->json($response);
    }

    /**
     * Update the distance and other distance-related fields for a job.
     */
    public function updateDistance(Request $request)
    {
        $distanceData = $request->all();

        $response = $this->distanceService->updateDistance($distanceData);

        return response()->json(['message' => $response]);
    }

    /**
     * Send immediate job email notifications.
     */
    public function storeJobEmail(Request $request)
    {
        $jobData = $request->all();

        $response = $this->jobService->sendImmediateJobEmail($jobData);

        return response()->json($response);
    }

    /**
     * Resend notifications to translators.
     */
    public function resendNotifications(Request $request)
    {
        $notificationData = $request->all();

        $response = $this->jobService->resendNotifications($notificationData);

        return response()->json($response);
    }

    /**
     * Resend SMS notifications to translators.
     */
    public function resendSMSNotifications(Request $request)
    {
        $smsData = $request->all();

        $response = $this->jobService->resendSMSNotifications($smsData);

        return response()->json($response);
    }
}
