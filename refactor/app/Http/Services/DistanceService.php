<?php

namespace DTApi\Services;

use DTApi\Models\Distance;
use DTApi\Models\Job;

/**
 * Class DistanceService
 * @package DTApi\Services
 */
class DistanceService
{
    /**
     * Update distance, time, and other related fields for a job.
     */
    public function updateDistance(array $data)
    {
        if (!isset($data['jobid'])) {
            return 'Job ID is required.';
        }

        $jobId = $data['jobid'];
        $distanceUpdates = [];
        $jobUpdates = [];

        if (!empty($data['distance'])) {
            $distanceUpdates['distance'] = $data['distance'];
        }

        if (!empty($data['time'])) {
            $distanceUpdates['time'] = $data['time'];
        }

        if (!empty($distanceUpdates)) {
            Distance::where('job_id', '=', $jobId)->update($distanceUpdates);
        }

        $jobUpdates = [
            'admin_comments' => $data['admincomment'] ?? '',
            'flagged' => $data['flagged'] == 'true' ? 'yes' : 'no',
            'session_time' => $data['session_time'] ?? '',
            'manually_handled' => $data['manually_handled'] == 'true' ? 'yes' : 'no',
            'by_admin' => $data['by_admin'] == 'true' ? 'yes' : 'no',
        ];

        Job::where('id', '=', $jobId)->update($jobUpdates);

        return 'Record updated!';
    }
}
