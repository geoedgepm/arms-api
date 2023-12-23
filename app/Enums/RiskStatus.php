<?php
   namespace App\Enums;

   class RiskStatus {
      const ACTIVE = 'Active';
      const DONE = 'Done';
      const CANCELLED = 'Cancelled';
      const NOT_STARTED = 'Not Started';
      const IN_PROGRESS = 'In Progress';
      const NEAR_DUE_DATE = 'Near Due Date';
      const OVERDUE = 'Overdue';
  }