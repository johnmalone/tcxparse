<?PHP

class ParseTCX
{
	protected $tcxFile;
	
	public function __construct($tcxFilePath)
	{
		$this->tcxFile = $tcxFilePath;
	}

	public function saveActivityXML()
	{
		$reader = new XMLReader();
		
		if (! $reader->open($this->tcxFile))
			return FALSE;
		while ($reader->read())
		{
			if ($reader->nodeType == XMLReader::ELEMENT and $reader->name == 'Activity')
			{
				$activityXML = $reader->readOuterXML();
				if (empty($activityXML))
					return FALSE;

				$unsavedActivity = UnsavedActivity::create(array(
							'activityXML' => $activityXML,
							));

				Auth::user()->unsavedActivitys()->save($unsavedActivity);
			}
		}

		return TRUE;
	}
}

