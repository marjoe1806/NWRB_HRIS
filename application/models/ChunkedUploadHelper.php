<?php
/**
 * Helper method for trimming chunked uploads.
 */
class ChunkedUploadHelper extends UploadHandler {
    /**
     * Custom "get" method to call our custom function.
     */
    public function get($print_response = true) {
        if ($print_response && isset($_GET['download'])) {
            return $this->download();
        }
        $file_name = $this->get_file_name_param();
        if ($file_name) {
            $response = array(
                substr($this->options['param_name'], 0, -1) => $this->get_file_object_trimmed($file_name)
            );
        } else {
            $response = array(
                $this->options['param_name'] => $this->get_file_objects()
            );
        }
        return $this->generate_response($response, $print_response);
    }

    /**
     * Gets a trimmed file object if the maxChunkSize has been given.
     */
    protected function get_file_object_trimmed($file_name) {
        $file_object = $this->get_file_object($file_name);
        if ($file_object != null) {
            // In case an upload was started and we have a max chunk size, trim it to the last full size.
            $max_chunk_size = $_GET['maxChunkSize'] || 0;
            if ($file_object->size > 0) && ($max_chunk_size > 0) {
                $truncated_bytes = floor($file_object->size / $max_chunk_size) * $max_chunk_size;
                if ($truncated_bytes < $file_object->size) {
                    $file_path = $this->get_upload_path($file_name, $this->get_version_param());
                    if (ftruncate(fopen($file_path, 'r+'), $truncated_bytes)) {
                        // Update file size to reflect truncated state
                        $file_object->size = filesize($file_path);
                    } else {
                        // Something went wrong while truncating, redo entire file
                        @unlink($file_path);
                        $file_object = null;
                    }
                }
            }
        }
        return $file_object;
    }
}
?>