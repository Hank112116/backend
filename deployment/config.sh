declare -xr REPO_ROOT=$( cd "$( dirname "${BASH_SOURCE[0]}" )/../" && pwd )

# Define exit codes
#
# Use defination in sysexits.h
# @link http://www.opensource.apple.com/source/Libc/Libc-320/include/sysexits.h
declare -ixr EX_OK=0;           # Successful termination
declare -ixr EX_USAGE=64;       # Command line usage error
declare -ixr EX_DATAERR=65;     # Data format error
declare -ixr EX_UNAVAILABLE=69; # Service unavailable
declare -ixr EX_SOFTWARE=70;    # Internal software error
declare -ixr EX_OSERR=71;       # System error
declare -ixr EX_TEMPFAIL=75;    # Temp failure; user is invited to retry
