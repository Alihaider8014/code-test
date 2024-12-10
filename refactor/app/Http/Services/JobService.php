<?php

namespace DTApi\Services;

use DTApi\Repository\BookingRepository;

/**
 * Class JobService
 * @package DTApi\Services
 */
class JobService
{
    protected $bookingRepository;

    /**
     * JobService constructor.
     *
     * @param BookingRepository $bookingRepository
     */
    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    /**
     * Get jobs for a user or admin.
     */
    public function getJobs($userId, $userType, $request)
    {
        if ($userId) {
            return $this->bookingRepository->getUsersJobs($userId);
        }

        if (in_array($userType, [env('ADMIN_ROLE_ID'), env('SUPERADMIN_ROLE_ID')])) {
            return $this->bookingRepository->getAll($request);
        }

        return null;
    }

    /**
     * Fetch job details with relationships.
     */
    public function getJobDetails($id)
    {
        return $this->bookingRepository->with('translatorJobRel.user')->find($id);
    }

    /**
     * Create a new job.
     */
    public function createJob(array $user, array $data)
    {
        return $this->bookingRepository->store($user, $data);
    }

    /**
     * Update an existing job.
     */
    public function updateJob($id, array $data, array $user)
    {
        $filteredData = array_except($data, ['_token', 'submit']);
        return $this->bookingRepository->updateJob($id, $filteredData, $user);
    }

    /**
     * End a job.
     */
    public function endJob(array $data)
    {
        return $this->bookingRepository->endJob($data);
    }

    /**
     * Accept a job.
     */
    public function acceptJob(array $data, array $user)
    {
        return $this->bookingRepository->acceptJob($data, $user);
    }

    /**
     * Accept a job by ID.
     */
    public function acceptJobById($jobId, array $user)
    {
        return $this->bookingRepository->acceptJobWithId($jobId, $user);
    }

    /**
     * Cancel a job.
     */
    public function cancelJob(array $data, array $user)
    {
        return $this->bookingRepository->cancelJobAjax($data, $user);
    }

    /**
     * Reopen a job.
     */
    public function reopenJob(array $data)
    {
        return $this->bookingRepository->reopen($data);
    }

    /**
     * Send email for immediate job notifications.
     */
    public function sendImmediateJobEmail(array $data)
    {
        return $this->bookingRepository->storeJobEmail($data);
    }

    /**
     * Send notifications to translators.
     */
    public function resendNotifications(array $data)
    {
        $job = $this->bookingRepository->find($data['jobid']);
        $jobData = $this->bookingRepository->jobToData($job);

        $this->bookingRepository->sendNotificationTranslator($job, $jobData, '*');

        return ['success' => 'Push sent'];
    }

    /**
     * Send SMS notifications to translators.
     */
    public function resendSMSNotifications(array $data)
    {
        $job = $this->bookingRepository->find($data['jobid']);

        try {
            $this->bookingRepository->sendSMSNotificationToTranslator($job);
            return ['success' => 'SMS sent'];
        } catch (\Exception $e) {
            return ['success' => $e->getMessage()];
        }
    }
}
