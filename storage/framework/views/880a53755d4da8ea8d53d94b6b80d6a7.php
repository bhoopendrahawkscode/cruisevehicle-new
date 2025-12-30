<table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #000000;" width="100%">
    <tbody>
        <tr>
            <td>
                <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #fff;" width="100%">
                    <tbody>
                        <tr>
                            <td>
                                <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 650px; margin: 0 auto;" width="650">
                                    <tbody>
                                        <tr>
                                            <td class="column column-1" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="100%">
                                                <table border="0" cellpadding="0" cellspacing="0" class="image_block block-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                                    <tbody>
                                                        <tr>
                                                            <td class="pad" style="padding-bottom:15px;padding-top:15px;width:100%;padding-right:0px;padding-left:0px;">
                                                                <div align="center" class="alignment" style="line-height:10px">
                                                                    <div style="max-width: 195px;">
                                                                        <img alt="your logo" src="https://82.octaldevs.com/cruisevehicle/public//storage/uploads/logo/8a33912b-242f-4c82-a537-c3e969746f62.png" style="display: block; border: 0px; width: 144px; height: 144px;" title="your logo" />
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #1E1F1F; color:#ffffff; width: 100%; max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; border-radius: 10px;">
    <tbody>
        <tr>
            <td style="padding: 20px; text-align: center;">
                <!-- Header -->
                <h1 style="margin: 0; font-size: 24px; color: #FFD700;">Insurance Expiry Reminder</h1>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px; font-size: 16px; line-height: 1.5;">
                <p style="margin: 0;">Dear <?php echo e($renewal->user->full_name); ?>,</p>
                <p style="margin: 15px 0;">This is a reminder that your insurance for <?php echo e($renewal->reg_no); ?> is expiring in <?php echo e($daysBefore); ?> days.</p>
                <p style="margin: 15px 0;">Please make sure to renew your insurance on time to avoid any inconvenience.</p>
                <p>Thank you,<br>Cruis Vehicle Management</p>
            </td>
        </tr>
        <!-- Footer -->
        <tr>
            <td style="padding: 20px; text-align: center; font-size: 12px; color: #999999;">
                <p style="margin: 0;">&copy; © 2024 Cruis- Vehicle Management. All rights reserved.</p>
            </td>
        </tr>
    </tbody>
</table>
<?php /**PATH /var/www/html/resources/views/email/insurance_reminder.blade.php ENDPATH**/ ?>